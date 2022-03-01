<!doctype html>
<html>
<head>
<meta charset="UTF-8" />
<title><?php echo $Title; ?> - <?php echo $Powered; ?></title>
<link rel="stylesheet" href="./css/install.css?v=9.0" />
</head>
<body>
<div class="wrap">
  <?php require './templates/header.php';?>
  <section class="section">
    <div class="step">
      <ul>
        <li class="on"><em>1</em>检测环境</li>
        <li class="current"><em>2</em>创建数据</li>
        <li><em>3</em>完成安装</li>
      </ul>
    </div>
<!--      -->
    <form id="J_install_form" action="index.php?step=4" method="post">
      <input type="hidden" name="force" value="0" />
      <div class="server">
        <table width="100%">
          <tr>
            <td class="td1" width="100">MySQL配置</td>
            <td class="td1" width="200">&nbsp;</td>
            <td class="td1">&nbsp;</td>
          </tr>
		  <tr>
            <td class="tar">数据库服务器：</td>
            <td><input type="text" name="dbhost" id="dbhost" value="192.168.0.199" class="input"></td>
            <td><div id="J_install_tip_dbhost"><span class="gray">数据库地址，本地为127.0.0.1</span></div></td>
          </tr>
          <tr>
            <td class="tar">数据库帐号：</td>
            <td><input type="text" name="dbuser" id="dbuser" value="root" class="input"></td>
            <td><div id="J_install_tip_dbuser"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库密码：</td>
            <td><input type="password" name="dbpw" id="dbpw" class="input"  autoComplete="off" onBlur="TestDbPwd(0)"></td>
            <td><div id="J_install_tip_dbpw"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库名：</td>
            <td><input type="text" name="dbname" id="dbname" value="testsql" class="input" onBlur="TestDbPwd(0)"></td>
            <td><div id="J_install_tip_dbname"></div></td>
          </tr>
          <tr>
            <td class="tar">数据库表前缀：</td>
            <td><input type="text" name="dbprefix" id="dbprefix" value="rf_" class="input" ></td>
            <td><div id="J_install_tip_dbprefix"><span class="gray">建议使用默认</span></div></td>
          </tr>
          <tr>
          	<td class="tar">演示数据：</td>
          	<td colspan="2"><input style="width:18px;height:18px;" type="checkbox" id="demo" name="demo" value="demo" checked></td>
          </tr>
        </table>
        <table width="100%">
          <tr>
            <td class="td1" width="100">管理员信息</td>
            <td class="td1" width="200">&nbsp;</td>
            <td class="td1">&nbsp;</td>
          </tr>
          <tr>
            <td class="tar">管理员帐号：</td>
            <td><input type="text" name="manager" id="manager" value="admin" class="input"></td>
            <td><div id="J_install_tip_manager"></div></td>
          </tr>
          <tr>
            <td class="tar">管理员密码：</td>
<!--              autoComplete="off" onBlur="TestDbPwd(0)-->
            <td><input type="password" name="manager_pwd" id="manager_pwd" class="input"  "></td>
            <td><div id="J_install_tip_manager_pwd"></div></td>
          </tr>
          <tr>
            <td class="tar">重复密码：</td>
            <td><input type="password" name="manager_ckpwd" id="manager_ckpwd" class="input" "></td>
            <td><div id="J_install_tip_manager_ckpwd"></div></td>
          </tr>

        </table>
          <table width="100%">
              <tr>
                  <td class="td1" width="100">Redis配置:</td>
                  <td class="td1" width="200">&nbsp;</td>
                  <td class="td1">&nbsp;</td>
              </tr>
              <tr>
                  <td class="tar">Redis地址：</td>
                  <td><input type="text" name="rbhost" id="rbhost" value="" class="input" ></td>
                  <td><div id="J_install_rbhost"><span class="gray">非必填项，本机为127.0.0.1</span></div></td>
              </tr>
              <tr>
                  <td class="tar">Redis密码：</td>
                  <td><input type="password" name="rbpw" id="rbpw" value="" class="input" autoComplete="off"></td>
                  <td><div id="J_install_tip_dbpw"></div><span class="gray">非必填项</span></td>
              </tr>

          </table>


        <div id="J_response_tips" style="display:none;"></div>
      </div>
      <div class="bottom tac"> <a href="./index.php?step=2" class="btn">上一步</a>
        <button type="button" onClick="checkForm();" class="btn btn_submit J_install_btn">创建数据</button>
      </div>
    </form>
  </section>
  <div  style="width:0;height:0;overflow:hidden;"> <img src="./images/install/pop_loading.gif"> </div>
  <script src="./js/jquery.js?v=9.0"></script>
  <script src="./js/validate.js?v=9.0"></script>
  <script src="./js/ajaxForm.js?v=9.0"></script>
  <script>

  function TestDbPwd(connect_db)
    {
        var dbHost = $('#dbhost').val();
        var dbUser = $('#dbuser').val();
        var dbPwd = $('#dbpw').val();
        var dbName = $('#dbname').val();
		var demo  =  $('#demo').val();
        var data={
            'dbHost': dbHost,
            'dbUser': dbUser,
            'dbPwd': dbPwd,
            'dbName': dbName,
            'demo': demo,
            rbhost: $('#rbhost').val(),
            rbport: $("#rbport").val(),
            rbselect: $("#rbselect").val(),
            rbpw: $('#rbpw').val(),
        };
        var url =  "<?php echo $_SERVER['PHP_SELF']; ?>?step=3&testdbpwd=1";
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            dataType:'JSON',
            beforeSend:function(){
            },
            success: function(data){
                if(data.code == 1){

					if(connect_db == 1)
					{
						$("#J_install_form").submit(); // ajax 验证通过后再提交表单
					}
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_success" style="">数据库链接成功</span>');
                    $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_success" style="">数据库可用</span>');
                }
                else if(data.code == 0)
                {
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');
                }
				else if(data.code == -1)
				{
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">请在mysql版本请选择5.7~8.0版本</span>');
				}
				// else if(data.code == -11)
                // {
                //     $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">请在mysql配置文件修sql-mode或sql_mode为NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION</span>');
                // }
				else if(data.code == -2)
				{
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_success" style="">数据库链接成功</span>');
                    $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" style="">数据库不存在</span>');
				}
                else if(data.code == -3)
                {
                    $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_success" style="">数据库链接成功</span>');
                    $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_error" style="">你的不是空数据库, 请更换一个数据库名字</span>');
                }
                else if(data.code == -4){
                    $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_success" style="">数据库可用</span>');
                    $('#J_install_rbhost').html('<span for="dbname" generated="true" class="tips_error" style="">Redis数据库没有启动或者密码错误</span>');
                }else if(data.code == -5){
                    $('#J_install_tip_dbname').html('<span for="dbname" generated="true" class="tips_success" style="">数据库可用</span>');
                    $('#J_install_rbhost').html('<span for="dbname" generated="true" class="tips_error" style="">Redis链接失败</span>');
                }else{
				    $('#dbpw').val("");
                }
            },
            complete:function(){
            },
            error:function(){
                $('#J_install_tip_dbpw').html('<span for="dbname" generated="true" class="tips_error" style="">数据库链接配置失败</span>');
            }
        });
    }



	function checkForm()
	{
			manager = $.trim($('#manager').val());				//用户名表单
			manager_pwd = $.trim($('#manager_pwd').val());				//密码表单
			manager_ckpwd = $.trim($('#manager_ckpwd').val());		//密码提示区

			if(manager.length == 0 )
			{
				alert('管理员账号不能为空');
				return false;
			}
            if(!(/^[a-zA-Z]{0,}$/.test(manager))){
                alert('账号必须为英文或者数字');
                return false;
            }
			if(manager_pwd.length < 6 )
			{
				alert('管理员密码必须6位数以上');
				return false;
			}
			if(manager_ckpwd !=  manager_pwd)
			{
				alert('两次密码不一致');
				return false;
			}
			TestDbPwd(1);
	}



</script>
</div>
<?php require './templates/footer.php';?>
</body>
</html>
