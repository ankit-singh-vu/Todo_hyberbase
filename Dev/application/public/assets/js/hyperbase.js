/**
 *
 * @type {{}}
 */
var HB = {

   interval_registry: {},

   setInterval: function(name, can_run, cb, interval) {
       if(typeof HB.interval_registry[name] === 'undefined') {
           HB.interval_registry[name] = setInterval(function() {
               can_run(function(run) { if(run === true) { cb(); } });
           }, interval);
       }
   }



};

$(document).ready(function() {

});