
const protocol = window.location.protocol
const hostname = window.location.hostname
const port = 8888
const baseUrl = protocol + '//' + hostname + ':' + port + '/prism/'

export default {
  prism: baseUrl + 'prism',
  dblink: baseUrl + 'dblink',
  testDbLink: baseUrl + 'testDBLink',
  dblinktemplate: baseUrl + 'dblinktemplate',
  userdb: baseUrl + 'userdb',
  charttemplate: baseUrl + 'charttemplate',
  chartinstance: baseUrl + 'chartinstance'
}
