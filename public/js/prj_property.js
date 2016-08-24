// var XT;
XT = XT || {};
(function(){
	this.device_index = function(){
		return this.grid_index('property', 'device', 'Device Manage');
	}
	
	this.property_index = function(){
		return this.grid_index('property', 'property', 'Property Manage');
	}
	
	this.device_type_index = function(){
		return this.grid_index('property', 'device_type', 'Device Type');
	}
	
	this.device_trace_index = function(){
		return this.grid_index('property', 'device_trace', 'Device Trace');
	}
}).apply(XT);
