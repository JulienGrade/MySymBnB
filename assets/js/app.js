const $ = require('jquery');

window.$ = $;
window.jQuery = $;

global.$= global.jQuery = $;

require('popper.js');
require('./bootstrap.min.js');