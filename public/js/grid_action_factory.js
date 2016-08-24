var KF_GRID_ACTIONS;
KF_GRID_ACTIONS = KF_GRID_ACTIONS || {};

var grid_action_factory = {
	grid_actions: {},
	get: function(grid, $params){
		// var tool = new kf_tool();
		var $db = $params['db'], $table = $params['table'], $container = $params['container'];
		var $param_id = JSON.stringify($params);
// XT.debug($params);
// XT.debug($param_id);
		if (this._init == undefined){
			this._init = 1;
			XT.loadFile('/js/gc_grid_action.js', 'js');
		}
		this.grid_actions[$db] = this.grid_actions[$db] || {};
		this.grid_actions[$db][$table] = this.grid_actions[$db][$table] || {};
		if (this.grid_actions[$db][$table][$param_id] == undefined){
			try{
				KF_GRID_ACTIONS[$db] = KF_GRID_ACTIONS[$db] || {};
				var jsFile = this.getJS($db, $table);
				XT.loadFile(jsFile, 'js');
				
				var o = new KF_GRID_ACTIONS[$db][$table](grid);
				this.grid_actions[$db][$table][$param_id] = o;
			}catch(e){
				this.grid_actions[$db][$table][$param_id] = new gc_grid_action(grid);
			}
		}
		return this.grid_actions[$db][$table][$param_id];
	},
	getJS: function($db, $table){
		var jsFile = '/js/' + $db + '/' + $table + '_action.js';
		return jsFile;
	}
};