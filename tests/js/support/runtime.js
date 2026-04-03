"use strict";

var path = require("path");

function getRequestedJqueryVersion() {
  return process.env.YII_JQUERY_VERSION === "4" ? "4" : "3";
}

function getJqueryPackageName() {
  return getRequestedJqueryVersion() === "4" ? "jquery4" : "jquery";
}

function getJquerySourcePath() {
  return path.join("node_modules", getJqueryPackageName(), "dist", "jquery.js");
}

function getPjaxSourcePath() {
  return path.join("src", "assets", "jquery.pjax.js");
}

module.exports = {
  getJqueryPackageName: getJqueryPackageName,
  getJquerySourcePath: getJquerySourcePath,
  getPjaxSourcePath: getPjaxSourcePath,
  getRequestedJqueryVersion: getRequestedJqueryVersion,
};
