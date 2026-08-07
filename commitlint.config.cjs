module.exports = {
  extends: ['@commitlint/config-conventional'],
  rules: {
    'type-enum': [
      2,
      'always',
      [
        'feat',      // tính năng mới
        'fix',       // bug fix
        'docs',      // thay đổi tài liệu
        'style',     // format, không ảnh hưởng logic
        'refactor',  // refactor, không phải feat hay fix
        'perf',      // cải thiện performance
        'test',      // thêm hoặc sửa test
        'chore',     // build process, tooling, dependency
        'revert',    // revert commit cũ
        'ci',        // CI/CD config
      ],
    ],
    'type-case': [2, 'always', 'lower-case'],
    'subject-case': [2, 'always', 'lower-case'],
    'subject-empty': [2, 'never'],
    'subject-full-stop': [2, 'never', '.'],
    'header-max-length': [2, 'always', 100],
    'scope-case': [2, 'always', 'lower-case'],
  },
};
