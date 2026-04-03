# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## 0.1.0 Under development

- feat: initial release jQuery integration layer extracted from `yii2-framework/core`.
- feat: add GitHub Actions workflow for easy coding standards.
- feat: add `foxy` configuration to `composer.json` extra section.
- chore: update documentation and configuration for jQuery integration layer, transitioning from `yii2-framework/core` to `yii2`.
- docs: update documentation with improved formatting and remove outdated development guide.
- feat: add dual compatibility for jQuery `3.7` and `4.0` while keeping jQuery `3.7` as the default dependency.
- fix: synchronize `yiiActiveForm` `validated` state before triggering `afterValidate` during submit validation.
