<script data-main="/js/default.js" src="/tpl/js/require.min.js"></script>

<script type="text/javascript">
define('elFinderConfig', {
	defaultOpts : {
		url : '<? if($CFG->USER->USER_ID == 85 || $CFG->USER->USER_ID == 1 || $CFG->USER->USER_ID ==481 || $CFG->USER->USER_ID ==133 || $CFG->USER->USER_ID ==536){ echo '/php/connector.minimal.php';} else { echo '/php/connector.minimal.manager.php';} ?>',
		 height   : 500,
		 useBrowserHistory: false,
		commandsOptions : {
			edit : {
				extraOptions : {
					creativeCloudApiKey : '',
					managerUrl : ''
				}
			}
			,quicklook : {
				// to enable CAD-Files and 3D-Models preview with sharecad.org
				sharecadMimes : ['image/vnd.dwg', 'image/vnd.dxf', 'model/vnd.dwf', 'application/vnd.hp-hpgl', 'application/plt', 'application/step', 'model/iges', 'application/vnd.ms-pki.stl', 'application/sat', 'image/cgm', 'application/x-msmetafile'],
				// to enable preview with Google Docs Viewer
				googleDocsMimes : ['application/pdf', 'image/tiff', 'application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/postscript', 'application/rtf'],
				// to enable preview with Microsoft Office Online Viewer
				// these MIME types override "googleDocsMimes"
				officeOnlineMimes : ['application/vnd.ms-office', 'application/msword', 'application/vnd.ms-word', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/vnd.oasis.opendocument.text', 'application/vnd.oasis.opendocument.spreadsheet', 'application/vnd.oasis.opendocument.presentation']
			}
		}
	},
	managers : {'elfinder': {} }
});
</script>






<h2><img alt="" src="/tpl/img/new/icon/7_red.png"> FILE BOX</h2>
<div class="white">
	<div id="elfinder"></div>
</div>

