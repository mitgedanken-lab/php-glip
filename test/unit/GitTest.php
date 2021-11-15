<?php

use Glip\GitBranch;
use Glip\GitCommit;
use Glip\Git;
use Glip\GitTree;
use Glip\GitBlob;
use Glip\GitPath;
use Glip\GitCommitStamp;


class GitTest extends \PHPUnit\Framework\TestCase
{

    public static $gitDirectory;

    /**
     * Setting up new Git repository in temporary dir'
     */
    public static function setUpBeforeClass(): void
    {
        $directory = __DIR__.'/temp/';
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        $directory .= date('YmdHis');
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        self::$gitDirectory = $directory;
        chdir(self::$gitDirectory);
        self::execGitCommand('init');
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {
    }

    protected static function execGitCommand($command, &$output = null)
    {
        $return_var = null;
        if (is_null($output))
            $output = array();
        exec('git ' . $command . ' 2>&1', $output, $return_var);
        return $return_var;
    }

    protected static function isGitFsck(&$output = null)
    {
        return 0 == self::execGitCommand('fsck --full', $output);
    }

    public function setUp(): void
    {
        chdir(self::$gitDirectory);
    }


    function testReadingFile()
    {
        

        file_put_contents('test1', 'data1');
        file_put_contents('test2', 'data2');
        self::execGitCommand('add .');
        self::execGitCommand('commit -m "Added 2 test files"');

        $git = new Git(self::$gitDirectory . DIRECTORY_SEPARATOR . '.git');
        $branch = $git['master'];
        $this->assertInstanceOf(GitBranch::class, $branch,
            'array access on Git returns a branch');
        $commit = $branch->getTip(true);
        $this->assertInstanceOf(GitCommit::class, $commit,
            '->getTip() returns GitCommit');
        $this->assertEquals('Added 2 test files', $commit->summary,
            'summary is properly read');
        $tree = $commit->tree;
        $this->assertInstanceOf(GitTree::class, $tree,
            'a commit holds a GitTree tree propery');

        $this->assertEquals( 2, count($tree),
            'GitTree is countable');

        $this->assertInstanceOf(IteratorAggregate::class, $tree,
            'GitTree implements iterator interface');

        foreach ($tree as $name => $node)
        {
            switch ($name)
            {
                case "test1":
                case "test2":
                    $this->assertInstanceOf(GitBlob::class, $node, "$name is a GitBlob");
                    $this->assertEquals('data'.substr($name,-1,1),  $node->data, "data is properly stored in a blob");
                    break;
                default:
                    $this->fail("unknown file '$name' found");
            }
        }

    }

    /**
     * Testing directories
     * @depends testReadingFile
     */
    function testDirectories()
    {
        mkdir('dir1');
        file_put_contents('dir1' . DIRECTORY_SEPARATOR . 'file', 'dir1');
        mkdir('dir2');
        file_put_contents('dir2' . DIRECTORY_SEPARATOR . 'file', 'dir2');
        self::execGitCommand('add .');
        self::execGitCommand('commit -m "Added 2 test dirs"');

        $git = new Git(self::$gitDirectory . DIRECTORY_SEPARATOR . '.git');
        $branch = $git['master'];
        $tree = $branch->getTip(true)->tree;
        $this->assertEquals(4, count($tree),
            'count(GitTree) returns number of files+number of dirs in the tree');

        foreach ($tree as $name => $node)
        {
            if (substr($name,0,3) == 'dir')
            {
                $this->assertInstanceOf(GitTree::class, $node, 'Directories are new GitTree objects');
                $this->assertInstanceOf(GitBlob::class, $node['file'], 'Files can be referenced by array access in the tree');
            }
        }

        $this->assertInstanceOf(GitBlob::class, $tree['dir1']['file'],
            'Multiple level array access returns the node');
        $this->assertInstanceOf(GitBlob::class, $tree['dir1/file'],
            'A full path returns the node');

        $path = new GitPath('dir1/file');
        $this->assertInstanceOf(GitBlob::class, $tree[(string)$path],
            'A full path as GitPath returns the node');
        $this->assertInstanceOf(GitBlob::class, $tree[array('dir1','file')],
            'A path referenced as array works');
    }


    /**
     * Test SHA computation when adding new objects
     * @depends testDirectories
     */
    function testSHAComputation()
    {
        $git = new Git(self::$gitDirectory . DIRECTORY_SEPARATOR . '.git');
        $branch = $git['master'];
        $commit = $branch->getTip(true);
        $newCommit = new GitCommit($commit);

        file_put_contents('newfile', 'newdata');
        self::execGitCommand('add .');
        self::execGitCommand('commit -m "Added 2 test files"');

        //load the new commit
        $commit = $branch->getTip(true);

        $newCommit['newfile'] = 'newdata';
        $this->assertInstanceOf(GitBlob::class, $newCommit['newfile'],
            'array setting automatically converts to GitBlob');

        //equalize modes
        $newCommit['newfile']->setMode($commit['newfile']->getMode());

        $this->assertEquals( $commit->tree->getSha()->hex(), $newCommit->tree->getSha()->hex(),
            'The correct tree sha is computed');

    }

    /**
     * Adding multiple level of directory blobs
     * @depends testSHAComputation
     */
    function testMultipleLevelDirectoryBlobs()
    {
        $git = new Git(self::$gitDirectory . DIRECTORY_SEPARATOR . '.git');
        $branch = $git['master'];
        $branch[array('newdir','subdir','file')] = 'test';
        $branch['newdir/subdir/subsubdir/file1'] = 'test';
        $branch['newdir/subdir/subsubdir/file2'] = 'test';
        $commit = $branch->commit(new GitCommitStamp(),'multilevel write');

        $this->assertInstanceOf(GitCommit::class, $commit,
            'committing on a branch returns a git commit object');

        $this->assertEquals($commit->getSha()->hex(), $branch->getTip()->getSha()->hex(),
            'The new tip of the branch is returned');

        $this->assertTrue(self::isGitFsck($output),
            'Git repos is still valid after writing '. implode("\n", $output));

        $this->assertEquals('multilevel write', $commit->summary,
            'the correct commit is loaded');

        $this->assertInstanceOf(GitTree::class, $commit['newdir'],
            'First level properly written');
        $this->assertInstanceOf(GitTree::class, $commit['newdir']['subdir'],
            'Second level properly written');
        $this->assertInstanceOf(GitBlob::class, $commit['newdir/subdir/file'],
            'Third level properly written');

        $blob = $commit['newdir/subdir/file'];
        $this->assertEquals('test', $blob->data,
            'multiple levels of directories automatically written');

    }

    /**
     * Removing a blob works
     * @depends testMultipleLevelDirectoryBlobs
     */
    function testRemovinBlob()
    {
        $git = new Git(self::$gitDirectory . DIRECTORY_SEPARATOR . '.git');
        $branch = $git['master'];
        unset($branch['newdir/subdir/subsubdir/file1']);
        $branch->commit(new GitCommitStamp(), 'test delete');

        $commit = $branch->getTip(true);
        $this->assertTrue(self::isGitFsck(),
            'Git repos is still valid after writing');

        $this->assertEquals('test delete', $commit->summary,
            'the correct commit is loaded');

        $this->assertInstanceOf(GitBlob::class, $commit['newdir/subdir/subsubdir/file2'],
            'Sibling of deleted object still exists');

        $this->assertNull($commit['newdir/subdir/subsubdir/file1'],
            'Object is deleted');

        unset($branch['newdir/subdir/subsubdir/file2']);
        $branch->commit(new GitCommitStamp(), 'test delete empty dirs');

        $commit = $branch->getTip(true);
        $this->assertTrue(self::isGitFsck(),
            'Git repos is still valid after writing');

        $this->assertEquals('test delete empty dirs', $commit->summary,
            'the correct commit is loaded');

        $this->assertNull($commit['newdir/subdir/subsubdir'],
            'Object and the parent tree are deleted');

    }
    
    
    /**
     * Test reading a compacted repository
     * @depends testRemovinBlob
     */
    function testReadingCompactedRepository()
    {
        $out = array();
        self::execGitCommand('gc', $out);

        $git = new Git(self::$gitDirectory . DIRECTORY_SEPARATOR . '.git');
        $branch = $git['master'];
        $blob = $branch['newdir/subdir/file'];
        $this->assertEquals('test', $blob->data,
            'Reading data from a compacted blob works');

    }
    
    /**
     * Test stash behaviour of a branch
     * @depends testReadingCompactedRepository
     */
    function testStashBehavior()
    {
        $git = new Git(self::$gitDirectory . DIRECTORY_SEPARATOR . '.git');
        $branch = $git['master'];
        $branch['test1'] = 'newValue';
        $this->assertEquals('newValue', $branch['test1']->data,
            'Reading objects from the branch returns the object in stash');
        $this->assertEquals('data1',$branch->getTip(true)->tree['test1']->data,
            'The tip of the branch still points to the old data');

        $branch->commit(new GitCommitStamp(),'testing stash behaviour');
        $this->assertEquals('newValue', $branch->getTip(true)->tree['test1']->data,
            'After committing the tip contains the new value');

        $branch['newstashdir/file'] = 'value';
        $this->assertNull($branch->getTip()->tree['newstashdir'],
            'GitTree does not exist in repository');
        $this->assertInstanceOf(GitTree::class, $branch['newstashdir'],
            'Branches create non existing GitTree from contents in their stash');
        $this->assertEquals( 1,count($branch['newstashdir']),
            'Just 1 file inside the GitTree');

        $found = false;
        foreach ($branch['/'] as $path => $obj)
        {
            if ($path == 'newstashdir')
            {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found,
            'The new tree is a child in the parent tree');

    }
}
