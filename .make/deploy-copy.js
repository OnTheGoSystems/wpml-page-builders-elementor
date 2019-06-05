#!/usr/bin/env node

const fs   = require('fs-extra');
const path = require('path');
const copy = require('recursive-copy');

const argv = require('yargs')
	.usage('Usage: [options]')
	.option('s', {
		description: 'The source path',
		alias:       'source',
		string:      true,
		default:     process.cwd(),
	})
	.option('t', {
		description:  'The target path',
		alias:        'target',
		demandOption: true,
		string:       true,
	})
	.option('d', {
		description: 'Debug',
		alias:       'debug',
		boolean:     true,
		default:     false
	})
	.demand('t')
	.argv;

const sourcePath = validatePath(path.normalize(argv.source));
const targetPath = path.normalize(argv.target);

emptyTargetDirectory().then(() => copySourceFiles());

function validatePath(pathToCheck) {
	if (pathToCheck && fs.existsSync(pathToCheck)) {
		return pathToCheck;
	}
	throw new Error(pathToCheck + ' does not exist!');
}


function emptyTargetDirectory() {

		return fs.remove(targetPath)
			.then(() => console.log(targetPath + ' removed.'))
			.catch(err => console.error(err));

}

function copySourceFiles() {
	return copy(sourcePath, targetPath, {
		dot:     false,
		junk:    false,
		results: true,
	})
	// .on(copy.events.COPY_FILE_COMPLETE, function (copyOperation) {
	// 	process.stdout.write('.');
	// 	// console.info('Copied to ' + copyOperation.dest);
	// })
		.on(copy.events.ERROR, function (error, copyOperation) {
			console.error('Unable to copy ' + copyOperation.dest);
		})
		.then(function (results) {
			console.info('\n' + results.length + ' file(s) copied');
		})
		.catch(function (error) {
			return console.error('\nCopy failed: ' + error);
		});
}
