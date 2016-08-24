// var tool = new kf_tool();

function gc_kf(params){
	this.params = params || {};
};

// gc_kf.prototype.tool = new kf_tool();
gc_kf.prototype.getParams = function(name){
	return XT.getParams(name, this.params);
}

gc_kf.prototype.setParams = function(p, forced){
	return XT.setParams(p, this.params, forced);
}

gc_kf.prototype.delParams = function(p){
	return XT.delParams(p, this.params);
}
