XT = XT || {};

(function(){
	this.admin_company = function(){
		return this.grid_index('useradmin', 'company', 'Company');
	}

	this.admin_user = function(){
		return this.grid_index('useradmin', 'users', 'User');
	}

	this.admin_user_group = function(){
		return this.grid_index('useradmin', 'groups', 'User Groups');
	}

	this.admin_user_role = function(){
		return this.grid_index('useradmin', 'role', 'User Roles');
	}

	this.db_admin_backup = function(){
		return this.newTab('/dbadmin/backup', '#mainContent', 'Backup DB');
	}
	
	this.db_admin_restore = function(){
		return this.newTab('/dbadmin/restore', '#mainContent', 'Restore DB');
	}
	
	this.db_admin_import = function(){
		return this.newTab('/dbadmin/import', '#mainContent', 'Import From Umbrella');
	}
	
	this.db_admin_userlog = function(){
		alert("dfdf");
		return this.grid_index('useradmin', 'log', 'User Log');
	}
	
	this.db_admin_relink = function(){
		return this.newTab('/dbadmin/relink', '#mainContent', 'Relink the testcase');
	}
	
	this.doc_index = function(){
		return this.grid_index('doc', 'doc', 'Documents');
	}
	
	this.doc_type_index = function(){
		return this.grid_index('doc', 'doc_type', 'Document Types');
	}
}).apply(XT);
