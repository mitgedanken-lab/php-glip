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

These can be assesed by loading the glip class into a variable (e.g $git = new glip();) then
using the command like on terminal but instead of "git add" it would be "$git->add();"

We divide git into high level ("porcelain") commands and low level ("plumbing") commands.
High-level commands (porcelain)

We separate the porcelain commands into the main commands and some ancillary user utilities.
Main porcelain commands */
function add(){
// git-add(1) } 

/* Add file contents to the index. */
function am(){ 
// git-am(1) }

/* Apply a series of patches from a mailbox. */
function archive(){
// git-archive(1) }

/* Create an archive of files from a named tree. */
function bisect(){
// git-bisect(1) }

/* Find by binary search the change that introduced a bug. */
function branch(){
// git-branch(1) }

/* List, create, or delete branches. */
function bundle(){
// git-bundle(1) }

/* Move objects and refs by archive. */
function checkout(){ 
// git-checkout(1) }

/* Checkout a branch or paths to the working tree. */
function cherry-pick(){ 
// git-cherry-pick(1) }

/* Apply the changes introduced by some existing commits. */
function citool(){
// git-citool(1) }

/* Graphical alternative to git-commit. */
function clean(){
// git-clean(1) }

/* Remove untracked files from the working tree. */
function clone(){
// git-clone(1) }

/* Clone a repository into a new directory. */
function commit(){
// git-commit(1) }

/* Record changes to the repository. */
function describe(){ 
// git-describe(1) }

/* Show the most recent tag that is reachable from a commit. */
function diff(){ 
// git-diff(1) }

/* Show changes between commits, commit and working tree, etc. */
function fetch(){ 
// git-fetch(1) }

/* Download objects and refs from another repository. */
function format-patch(){
// git-format-patch(1) }

/* Prepare patches for e-mail submission. */
function gc(){ 
// git-gc(1) }

/* Cleanup unnecessary files and optimize the local repository. */
function grep(){ 
// git-grep(1) }

/* Print lines matching a pattern. */
function gui(){ 
// git-gui(1) }

/* A portable graphical interface to Git. */
function init(){ 
// git-init(1) }

/* Create an empty git repository or reinitialize an existing one. */
function log(){
// git-log(1) }

/* Show commit logs. */
function merge(){ 
// git-merge(1) }

/* Join two or more development histories together. */
function mv(){ 
// git-mv(1) }

/* Move or rename a file, a directory, or a symlink. */
function notes(){ 
// git-notes(1) }

/* Add or inspect object notes. */
function pull(){
// git-pull(1) }

/* Fetch from and merge with another repository or a local branch. */
function push(){
// git-pull(1) }

/* Update remote refs along with associated objects. */
function rebase(){ 
// git-rebase(1) }

/* Forward-port local commits to the updated upstream head. */
function reset(){ 
// git-reset(1) }

/* Reset current HEAD to the specified state. */
function revert(){
// git-revert(1) }

/* Revert some existing commits. */
function rm(){ 
// git-rm(1) }

/* Remove files from the working tree and from the index. */
function shortlog(){ 
// git-shortlog(1) }

/* Summarize git log output. */
function show(){
// git-show(1) }

/* Show various types of objects. */
function stash(){
// git-stash(1) }

/* Stash the changes in a dirty working directory away. */
function status(){ 
// git-status(1) }

/* Show the working tree status. */
function submodule(){ 
// git-submodule(1) }

/* Initialize, update or inspect submodules. */
function tag(){
// git-tag(1) }

/* Create, list, delete or verify a tag object signed with GPG. */
function gitk(){
// gitk(1) }

/* The git repository browser.
Ancillary Commands

Manipulators:*/
function config(){ 
// git-config(1) }

/* Get and set repository or global options. */
function fast-export(){ 
// git-fast-export(1) }

/* Git data exporter. */
function fast-import(){ 
// git-fast-import(1) }

/* Backend for fast Git data importers. */
function filter-branch(){ 
// git-filter-branch(1) }

/* Rewrite branches. */
function lost-found(){ 
// git-lost-found(1) }

/* (deprecated) Recover lost refs that luckily have not yet been pruned. */
function mergetool(){ 
// git-mergetool(1) }

/* Run merge conflict resolution tools to resolve merge conflicts. */
function pack-refs(){ 
// git-pack-refs(1) }

/* Pack heads and tags for efficient repository access. */
function prune(){
// git-prune(1) }

/* Prune all unreachable objects from the object database. */
function reflog(){
// git-reflog(1) }

/* Manage reflog information. */
function relink(){
// git-relink(1) }

/* Hardlink common objects in local repositories. */
function remote(){
// git-remote(1) }

/* manage set of tracked repositories. */
function repack(){ 
// git-repack(1) }

/* Pack unpacked objects in a repository. */
function replace(){
// git-replace(1) }

/* Create, list, delete refs to replace objects. */
function repo-config(){
// git-repo-config(1) }

/* (deprecated) Get and set repository or global options.

Interrogators: */
function annotate(){ 
// git-annotate(1) }

/* Annotate file lines with commit information. */
function blame(){
// git-blame(1) }

/* Show what revision and author last modified each line of a file. */ 
function cherry(){ 
// git-cherry(1) }

/* Find commits not merged upstream. */
function count-objects(){ 
// git-count-objects(1) }

/* Count unpacked number of objects and their disk consumption. */
function difftool(){
// git-difftool(1) }

/* Show changes using common diff tools. */
function fsck(){
// git-fsck(1) }

/* Verifies the connectivity and validity of the objects in the database. */
function get-tar-commit-id(){
// git-get-tar-commit-id(1) }

/* Extract commit ID from an archive created using git-archive. */
function help(){
// git-help(1) }

/* display help information about git. */
function instaweb(){
// git-instaweb(1) }

/* Instantly browse your working repository in gitweb. */
function merge-tree(){
// git-merge-tree(1) }

/* Show three-way merge without touching index. */
function rerere(){
// git-rerere(1) }

/* Reuse recorded resolution of conflicted merges. */
function rev-parse(){
// git-rev-parse(1) }

/* Pick out and massage parameters. */
function show-branch(){ 
// git-show-branch(1) }

/* Show branches and their commits. */
function verify-tag(){ 
// git-verify-tag(1) }

/* Check the GPG signature of tags. */
function whatchanged(){ 
// git-whatchanged(1) }

/* Show logs with difference each commit introduces.

Interacting with Others
/* These commands are to interact with foreign SCM and with other people via patch over e-mail. */
function archimport(){
// git-archimport(1) }

/* Import an Arch repository into git. */
function cvsexportcommit(){ 
// git-cvsexportcommit(1) }

/* Export a single commit to a CVS checkout. */
function cvsimport(){ 
// git-cvsimport(1) }

/* Salvage your data out of another SCM people love to hate. */
function cvsserver(){
// git-cvsserver(1) }

/* A CVS server emulator for git. */
function imap-send(){ 
// git-imap-send(1) }

/* Send a collection of patches from stdin to an IMAP folder. */
function quiltimport(){ 
// git-quiltimport(1) }

/* Applies a quilt patchset onto the current branch. */
function request-pull(){ 
// git-request-pull(1) }

/* Generates a summary of pending changes. */
function send-email(){ 
// git-send-email(1) }

/* Send a collection of patches as emails. */
function svn(){ 
// git-svn(1) }

/* Bidirectional operation between a Subversion repository and git.
Low-level commands (plumbing)

Although git includes its own porcelain layer, its low-level commands are sufficient to support development of alternative porcelains. Developers of such porcelains might start by reading about git-update-index(1) } and git-read-tree(1) }.

The interface (input, output, set of options and the semantics) to these low-level commands are meant to be a lot more stable than Porcelain level commands, because these commands are primarily for scripted use. The interface to Porcelain commands on the other hand are subject to change in order to improve the end user experience.

The following description divides the low-level commands into commands that manipulate objects (in the repository, index, and working tree), commands that interrogate and compare objects, and commands that move objects and references between repositories.
Manipulation commands */
function apply(){ 
// git-apply(1) }

/* Apply a patch to files and/or to the index. */
function checkout-index(){ 
// git-checkout-index(1) }

/* Copy files from the index to the working tree. */
function commit-tree(){ 
// git-commit-tree(1) }

/* Create a new commit object. */
function hash-object(){
// git-hash-object(1) }

/* Compute object ID and optionally creates a blob from a file. */
function index-pack(){ 
// git-index-pack(1) }

/* Build pack index file for an existing packed archive. */
function merge-file(){ 
// git-merge-file(1) }

/* Run a three-way file merge. */
function merge-index(){ 
// git-merge-index(1) }

/* Run a merge for files needing merging. */
function mktag(){ 
// git-mktag(1) }

/* Creates a tag object. */
function mktree(){ 
// git-mktree(1) }

/* Build a tree-object from ls-tree formatted text. */
function pack-objects(){ 
// git-pack-objects(1) }

/* Create a packed archive of objects. */
function prune-packed(){ 
// git-prune-packed(1) }

/* Remove extra objects that are already in pack files. */
function read-tree(){
// git-read-tree(1) }

/* Reads tree information into the index. */
function symbolic-ref(){ 
// git-symbolic-ref(1) }

/* Read and modify symbolic refs. */
function unpack-objects(){ 
// git-unpack-objects(1) }

/* Unpack objects from a packed archive. */
function update-index(){ 
// git-update-index(1) }

/* Register file contents in the working tree to the index. */
function update-ref(){ 
// git-update-ref(1) }

/* Update the object name stored in a ref safely. */
function write-tree(){
// git-write-tree(1) }

/* Create a tree object from the current index. */
Interrogation commands
function cat-file(){
// git-cat-file(1) }

/* Provide content or type and size information for repository objects. */
function diff-files(){ 
// git-diff-files(1) }

/* Compares files in the working tree and the index. */
function diff-index(){ 
// git-diff-index(1) }

/* Compares content and mode of blobs between the index and repository. */
function diff-tree(){ 
// git-diff-tree(1) }

/* Compares the content and mode of blobs found via two tree objects. */
function for-each-ref(){ 
// git-for-each-ref(1) }

/* Output information on each ref. */
function ls-files(){ 
// git-ls-files(1) }

/* Show information about files in the index and the working tree. */
function ls-remote(){ 
// git-ls-remote(1) }

/* List references in a remote repository. */
function ls-tree(){
// git-ls-tree(1) }

/* List the contents of a tree object. */
function merge-base(){ 
// git-merge-base(1) }

/* Find as good common ancestors as possible for a merge. */
function name-rev(){
// git-name-rev(1) }

/* Find symbolic names for given revs. */
function pack-redundant(){ 
// git-pack-redundant(1) }

/* Find redundant pack files. */
function rev-list(){ 
// git-rev-list(1) }

/* Lists commit objects in reverse chronological order. */
function show-index(){ 
// git-show-index(1) }

/* Show packed archive index. */
function show-ref(){ 
// git-show-ref(1) }

/* List references in a local repository. */
function tar-tree(){ 
// git-tar-tree(1) }

/* (deprecated) Create a tar archive of the files in the named tree object. */
function unpack-file(){ 
// git-unpack-file(1) }

/* Creates a temporary file with a blob’s contents. */
function var(){ 
// git-var(1) }

/* Show a git logical variable. */
function verify-pack(){ 
// git-verify-pack(1) }

/* Validate packed git archive files. 

In general, the interrogate commands do not touch the files in the working tree.
Synching repositories */
function daemon(){ 
// git-daemon(1) }

/* A really simple server for git repositories. */
function fetch-pack(){ 
// git-fetch-pack(1) }

/* Receive missing objects from another repository. */
function http-backend(){ 
// git-http-backend(1) }

/* Server side implementation of Git over HTTP. */
function send-pack(){
// git-send-pack(1) }

/* Push objects over git protocol to another repository. */
function update-server-info(){ 
// git-update-server-info(1) }

/* Update auxiliary info file to help dumb servers. 

The following are helper commands used by the above; end users typically do not use them directly. */
function http-fetch(){ 
// git-http-fetch(1) }

/* Download from a remote git repository via HTTP. */
function http-push(){
// git-http-push(1) }

/* Push objects over HTTP/DAV to another repository. */
function parse-remote(){ 
// git-parse-remote(1) }

/* Routines to help parsing remote repository access parameters. */
function receive-pack(){
// git-receive-pack(1) }

/* Receive what is pushed into the repository. */
function shell(){
// git-shell(1) }

/* Restricted login shell for Git-only SSH access. */
function upload-archive(){
// git-upload-archive(1) }

/* Send archive back to git-archive. */
function upload-pack(){
// git-upload-pack(1) }

/* Send objects packed back to git-fetch-pack.
Internal helper commands

These are internal helper commands used by other commands; end users typically do not use them directly. */
function check-attr(){
// git-check-attr(1) }

/* Display gitattributes information. */
function check-ref-format(){
// git-check-ref-format(1) }

/* Ensures that a reference name is well formed. */
function fmt-merge-msg(){
// git-fmt-merge-msg(1) }

/* Produce a merge commit message. */
function mailinfo(){
// git-mailinfo(1) }

/* Extracts patch and authorship from a single e-mail message. */
function mailsplit(){
// git-mailsplit(1) }

/* Simple UNIX mbox splitter program. */
function merge-one-file(){
// git-merge-one-file(1) }

/* The standard helper program to use with git-merge-index. */
function patch-id(){
// git-patch-id(1) }

/* Compute unique ID for a patch. */
function peek-remote(){
// git-peek-remote(1) }

/* (deprecated) List the references in a remote repository. */
function sh-setup(){
// git-sh-setup(1) }

/* Common git shell script setup code. */
function stripspace(){
// git-stripspace(1) }

/* Filter out empty lines. */
}
