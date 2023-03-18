module.exports = {
    pattern: ':type/:name',
    params: {
      type: [
        'fix',
        'hotfix',
        'feat',
        'feature',
        'docs',
        'misc',
        'improve',
        'introduce',
      ],
      name: ['[a-z0-9-]+'],
    },
    prohibited: [
      'ci',
      'wip',
      'main',
      'dev',
      'develop',
      'production',
      'test',
      'build',
      'master',
      'release',
    ],
  };