XT = XT || {};

(function(){
	this.mcu_device_index = function(){
		return this.grid_index('mcu', 'device', 'Devices');
	}
	
	this.mcu_device_ver_index = function(){
		return this.grid_index('mcu', 'zzvw_device_ver', 'Device Vers');
	}
}).apply(XT);
