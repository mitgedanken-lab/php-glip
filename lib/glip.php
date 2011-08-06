<?php
/*
 * Copyright (C) 2009 Patrik Fimml
 *
 * This file is part of glip.
 *
 * glip is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.

 * glip is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with glip.  If not, see <http://www.gnu.org/licenses/>.
 */

$old_include_path = set_include_path(dirname(__FILE__));
require_once('git.class.php');
set_include_path($old_include_path);

class glip {
/* 
GIT COMMANDS

We divide git into high level ("porcelain") commands and low level ("plumbing") commands.
High-level commands (porcelain)

We separate the porcelain commands into the main commands and some ancillary user utilities.
Main porcelain commands
git-add(1) 

 Add file contents to the index. 
git-am(1) 

 Apply a series of patches from a mailbox. 
git-archive(1) 

 Create an archive of files from a named tree. 
git-bisect(1) 

 Find by binary search the change that introduced a bug. 
git-branch(1) 

 List, create, or delete branches. 
git-bundle(1) 

 Move objects and refs by archive. 
git-checkout(1) 

 Checkout a branch or paths to the working tree. 
git-cherry-pick(1) 

 Apply the changes introduced by some existing commits. 
git-citool(1) 

 Graphical alternative to git-commit. 
git-clean(1) 

 Remove untracked files from the working tree. 
git-clone(1) 

 Clone a repository into a new directory. 
git-commit(1) 

 Record changes to the repository. 
git-describe(1) 

 Show the most recent tag that is reachable from a commit. 
git-diff(1) 

 Show changes between commits, commit and working tree, etc. 
git-fetch(1) 

 Download objects and refs from another repository. 
git-format-patch(1) 

 Prepare patches for e-mail submission. 
git-gc(1) 

 Cleanup unnecessary files and optimize the local repository. 
git-grep(1) 

 Print lines matching a pattern. 
git-gui(1) 

 A portable graphical interface to Git. 
git-init(1) 

 Create an empty git repository or reinitialize an existing one. 
git-log(1) 

 Show commit logs. 
git-merge(1) 

 Join two or more development histories together. 
git-mv(1) 

 Move or rename a file, a directory, or a symlink. 
git-notes(1) 

 Add or inspect object notes. 
git-pull(1) 

 Fetch from and merge with another repository or a local branch. 
git-push(1) 

 Update remote refs along with associated objects. 
git-rebase(1) 

 Forward-port local commits to the updated upstream head. 
git-reset(1) 

 Reset current HEAD to the specified state. 
git-revert(1) 

 Revert some existing commits. 
git-rm(1) 

 Remove files from the working tree and from the index. 
git-shortlog(1) 

 Summarize git log output. 
git-show(1) 

 Show various types of objects. 
git-stash(1) 

 Stash the changes in a dirty working directory away. 
git-status(1) 

 Show the working tree status. 
git-submodule(1) 

 Initialize, update or inspect submodules. 
git-tag(1) 

 Create, list, delete or verify a tag object signed with GPG. 
gitk(1) 

 The git repository browser. 
Ancillary Commands

Manipulators:
git-config(1) 

 Get and set repository or global options. 
git-fast-export(1) 

 Git data exporter. 
git-fast-import(1) 

 Backend for fast Git data importers. 
git-filter-branch(1) 

 Rewrite branches. 
git-lost-found(1) 

 (deprecated) Recover lost refs that luckily have not yet been pruned. 
git-mergetool(1) 

 Run merge conflict resolution tools to resolve merge conflicts. 
git-pack-refs(1) 

 Pack heads and tags for efficient repository access. 
git-prune(1) 

 Prune all unreachable objects from the object database. 
git-reflog(1) 

 Manage reflog information. 
git-relink(1) 

 Hardlink common objects in local repositories. 
git-remote(1) 

 manage set of tracked repositories. 
git-repack(1) 

 Pack unpacked objects in a repository. 
git-replace(1) 

 Create, list, delete refs to replace objects. 
git-repo-config(1) 

 (deprecated) Get and set repository or global options. 

Interrogators:
git-annotate(1) 

 Annotate file lines with commit information. 
git-blame(1) 

 Show what revision and author last modified each line of a file. 
git-cherry(1) 

 Find commits not merged upstream. 
git-count-objects(1) 

 Count unpacked number of objects and their disk consumption. 
git-difftool(1) 

 Show changes using common diff tools. 
git-fsck(1) 

 Verifies the connectivity and validity of the objects in the database. 
git-get-tar-commit-id(1) 

 Extract commit ID from an archive created using git-archive. 
git-help(1) 

 display help information about git. 
git-instaweb(1) 

 Instantly browse your working repository in gitweb. 
git-merge-tree(1) 

 Show three-way merge without touching index. 
git-rerere(1) 

 Reuse recorded resolution of conflicted merges. 
git-rev-parse(1) 

 Pick out and massage parameters. 
git-show-branch(1) 

 Show branches and their commits. 
git-verify-tag(1) 

 Check the GPG signature of tags. 
git-whatchanged(1) 

 Show logs with difference each commit introduces. 
Interacting with Others

These commands are to interact with foreign SCM and with other people via patch over e-mail.
git-archimport(1) 

 Import an Arch repository into git. 
git-cvsexportcommit(1) 

 Export a single commit to a CVS checkout. 
git-cvsimport(1) 

 Salvage your data out of another SCM people love to hate. 
git-cvsserver(1) 

 A CVS server emulator for git. 
git-imap-send(1) 

 Send a collection of patches from stdin to an IMAP folder. 
git-quiltimport(1) 

 Applies a quilt patchset onto the current branch. 
git-request-pull(1) 

 Generates a summary of pending changes. 
git-send-email(1) 

 Send a collection of patches as emails. 
git-svn(1) 

 Bidirectional operation between a Subversion repository and git. 
Low-level commands (plumbing)

Although git includes its own porcelain layer, its low-level commands are sufficient to support development of alternative porcelains. Developers of such porcelains might start by reading about git-update-index(1) and git-read-tree(1).

The interface (input, output, set of options and the semantics) to these low-level commands are meant to be a lot more stable than Porcelain level commands, because these commands are primarily for scripted use. The interface to Porcelain commands on the other hand are subject to change in order to improve the end user experience.

The following description divides the low-level commands into commands that manipulate objects (in the repository, index, and working tree), commands that interrogate and compare objects, and commands that move objects and references between repositories.
Manipulation commands
git-apply(1) 

 Apply a patch to files and/or to the index. 
git-checkout-index(1) 

 Copy files from the index to the working tree. 
git-commit-tree(1) 

 Create a new commit object. 
git-hash-object(1) 

 Compute object ID and optionally creates a blob from a file. 
git-index-pack(1) 

 Build pack index file for an existing packed archive. 
git-merge-file(1) 

 Run a three-way file merge. 
git-merge-index(1) 

 Run a merge for files needing merging. 
git-mktag(1) 

 Creates a tag object. 
git-mktree(1) 

 Build a tree-object from ls-tree formatted text. 
git-pack-objects(1) 

 Create a packed archive of objects. 
git-prune-packed(1) 

 Remove extra objects that are already in pack files. 
git-read-tree(1) 

 Reads tree information into the index. 
git-symbolic-ref(1) 

 Read and modify symbolic refs. 
git-unpack-objects(1) 

 Unpack objects from a packed archive. 
git-update-index(1) 

 Register file contents in the working tree to the index. 
git-update-ref(1) 

 Update the object name stored in a ref safely. 
git-write-tree(1) 

 Create a tree object from the current index. 
Interrogation commands
git-cat-file(1) 

 Provide content or type and size information for repository objects. 
git-diff-files(1) 

 Compares files in the working tree and the index. 
git-diff-index(1) 

 Compares content and mode of blobs between the index and repository. 
git-diff-tree(1) 

 Compares the content and mode of blobs found via two tree objects. 
git-for-each-ref(1) 

 Output information on each ref. 
git-ls-files(1) 

 Show information about files in the index and the working tree. 
git-ls-remote(1) 

 List references in a remote repository. 
git-ls-tree(1) 

 List the contents of a tree object. 
git-merge-base(1) 

 Find as good common ancestors as possible for a merge. 
git-name-rev(1) 

 Find symbolic names for given revs. 
git-pack-redundant(1) 

 Find redundant pack files. 
git-rev-list(1) 

 Lists commit objects in reverse chronological order. 
git-show-index(1) 

 Show packed archive index. 
git-show-ref(1) 

 List references in a local repository. 
git-tar-tree(1) 

 (deprecated) Create a tar archive of the files in the named tree object. 
git-unpack-file(1) 

 Creates a temporary file with a blob’s contents. 
git-var(1) 

 Show a git logical variable. 
git-verify-pack(1) 

 Validate packed git archive files. 

In general, the interrogate commands do not touch the files in the working tree.
Synching repositories
git-daemon(1) 

 A really simple server for git repositories. 
git-fetch-pack(1) 

 Receive missing objects from another repository. 
git-http-backend(1) 

 Server side implementation of Git over HTTP. 
git-send-pack(1) 

 Push objects over git protocol to another repository. 
git-update-server-info(1) 

 Update auxiliary info file to help dumb servers. 

The following are helper commands used by the above; end users typically do not use them directly.
git-http-fetch(1) 

 Download from a remote git repository via HTTP. 
git-http-push(1) 

 Push objects over HTTP/DAV to another repository. 
git-parse-remote(1) 

 Routines to help parsing remote repository access parameters. 
git-receive-pack(1) 

 Receive what is pushed into the repository. 
git-shell(1) 

 Restricted login shell for Git-only SSH access. 
git-upload-archive(1) 

 Send archive back to git-archive. 
git-upload-pack(1) 

 Send objects packed back to git-fetch-pack. 
Internal helper commands

These are internal helper commands used by other commands; end users typically do not use them directly.
git-check-attr(1) 

 Display gitattributes information. 
git-check-ref-format(1) 

 Ensures that a reference name is well formed. 
git-fmt-merge-msg(1) 

 Produce a merge commit message. 
git-mailinfo(1) 

 Extracts patch and authorship from a single e-mail message. 
git-mailsplit(1) 

 Simple UNIX mbox splitter program. 
git-merge-one-file(1) 

 The standard helper program to use with git-merge-index. 
git-patch-id(1) 

 Compute unique ID for a patch. 
git-peek-remote(1) 

 (deprecated) List the references in a remote repository. 
git-sh-setup(1) 

 Common git shell script setup code. 
git-stripspace(1) 

 Filter out empty lines. */
}
