var http = require('http');
var config = require('config');
var console = require('console');

module.exports.function = function getSectorData (sectorNumber) {
  var json = http.getUrl(config.get('backend.url')+'/data/aqi/'+sectorNumber, {format: 'json'});
  var node = json.data[0]
  var output = node ? node.value : null;
  return output;
}
