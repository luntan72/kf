var KF_GRIDS;
KF_GRIDS = KF_GRIDS || {};

var grid_factory = {
	objs: {},
	get: function($db, $table, $params){
		// var tool = new kf_tool();
		if (this._init == undefined){
			this._init = 1;
			XT.loadFile('/js/gc_kf.js', 'js');
			XT.loadFile('/js/gc_db_table.js', 'js');
			XT.loadFile('/js/gc_grid.js', 'js');
		}
		$params = $params || {};
		$params['container'] = $params['container'] || 'mainContent';
		$params['db'] = $params['db'] || $db;
		$params['table'] = $params['table'] || $table;
		var param_id = JSON.stringify($params);
// XT.debug($params);
// XT.debug(param_id)		;
		this.objs[$db] = this.objs[$db] || {};
		this.objs[$db][$table] = this.objs[$db][$table] || {};
		if (this.objs[$db][$table][param_id] == undefined){
			try{
				KF_GRIDS[$db] = KF_GRIDS[$db] || {};
				// var jsFile = this.getJS($db, $table);
				// XT.loadFile(jsFile, 'js');
				
				this.objs[$db][$table][param_id] = new KF_GRIDS[$db][$table]($params);
			}catch(e){
				this.objs[$db][$table][param_id] = new gc_grid($params);
			}
		}
		var o = this.objs[$db][$table][param_id];
		
		o.ready();
		return this.objs[$db][$table][param_id];
	},
	getJS: function($db, $table){
		var jsFile = '/js/' + $db + '/' + $table + '.js';
		return jsFile;
	}
};