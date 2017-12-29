function sleep(ms) {
	return new Promise(resolve => setTimeout(resolve,ms));
}
async function textaktualisieren() {
	while(true) {
		for(var i=0;i<content.length;i++) {
			//console.log("i:".i."; ".content[i].pfad+":"+content[i].dauer);
			if(content[i].typ==0) {
				$("#text").load(content[i].pfad);
				//$("#text").show();
			}
			await sleep(content[i].dauer);
		}
	}
}
textaktualisieren();