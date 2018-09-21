<link rel="stylesheet" href="assets/plugins/dhtmlxsuite/dhtmlx.css" type="text/css" media="screen" title="no title" charset="utf-8">
<script src="assets/plugins/dhtmlxsuite/dhtmlx.js"></script>
<body onload="sasmenuLoad();">
	<div style="height: 400px; position: relative;">
		<div id="contextArea" style="position: absolute; left: 100px; top: 100px; width: 100px; height: 60px; border: #C1C1C1 1px solid; background-color: #E7F4FF;"></div>
	</div>
</body>

<script type="text/javascript">
 var sascontext;
 function sasmenuLoad() {
   sascontext = new dhtmlXMenuObject("contextArea");
   sascontext.setIconsPath("data/menu/imgs/");
   sascontext.renderAsContextMenu();
   sascontext.loadStruct("data/menu/dhxmenu.xml");
   sascontext.attachEvent("onClick",sasmenuClick);

 }

 function sasmenuClick(menuitemId,type){
   console.log(menuitemId);
 }
</script>
