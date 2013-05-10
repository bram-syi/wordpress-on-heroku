define([ 
  './model'
], function(
  Model
) {
  return Model({
    findOne: '/a/api/team.php?team={team}',
    findAll: '/a/api/team.php',
    create: '/a/api/team.php',
    update: '/a/api/team.php',
    destroy: '/a/api/team.php',

    type_id: 'team',
    id: "team"
  }, {
    getTitle: function() { return this.team_title }
  });
});
