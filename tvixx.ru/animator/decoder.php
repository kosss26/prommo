<div id='decode'></div>
<input type='text' id='cod'>
<input type='button' value='_mput' onclick="code = cod.value;">

<script>
	var code="";
	var NicoCode = '{"0":[[[]]],"type":["Name"],"rasa":["красный"],"img":"GOL_app_hero_goblin.png"}';;
	//var pars;
	var parsNicoCode;
	var mesto = 0;
	var anims = 0;
	
	
	function _mput(per1,pars)
	{
		for(var i = 0; i < pars.length; i++)
		{
		
			parsNicoCode[0][0][0][i] = [];
			parsNicoCode[0][0][0][i][0] = pars[i][3];
			parsNicoCode[0][0][0][i][1] = pars[i][4];
			parsNicoCode[0][0][0][i][2] = pars[i][5];
			parsNicoCode[0][0][0][i][3] = pars[i][6];
			parsNicoCode[0][0][0][i][4] = pars[i][1]+60;
			parsNicoCode[0][0][0][i][5] = pars[i][2]+60;
			parsNicoCode[0][0][0][i][6] = pars[i][5];
			parsNicoCode[0][0][0][i][7] = pars[i][6];
			parsNicoCode[0][0][0][i][8] = pars[i][7];
			if(pars[i][0] == -1){
				parsNicoCode[0][0][0][i][9] = "-1";
			}else{
			parsNicoCode[0][0][0][i][9] = "Элемент";
			}
			
		}
		decode.innerHTML += JSON.stringify(parsNicoCode);*/
		
	}

	
	
	
</script>