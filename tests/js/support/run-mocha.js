"use strict";

var spawnSync = require("child_process").spawnSync;

var version = process.argv[2] || "3";
var extraArgs = process.argv.slice(3);
var mochaBin = require.resolve("mocha/bin/mocha");
var mochaArgs = [
  mochaBin,
  "tests/js/tests/*.test.js",
  "--timeout",
  "0",
  "--colors",
].concat(extraArgs);

var result = spawnSync(process.execPath, mochaArgs, {
  env: {
    ...process.env,
    YII_JQUERY_VERSION: version,
  },
  stdio: "inherit",
});

if (result.error) {
  throw result.error;
}

process.exit(result.status === null ? 1 : result.status);
