<template>
<div class="bodyy">
  <div class="main">
  	<div class="center">
  		<el-form :model="ruleForm2" status-icon :rules="rules2" ref="ruleForm2" class="demo-ruleForm" @submit.native.prevent>
        <el-form-item label="用户名" prop="username">
          <i class="el-icon-tickets Cone"> | </i><el-input type="text" v-model="ruleForm2.username" autocomplete="off" class="elinp" placeholder=" 用户名"></el-input>
        </el-form-item>
        <el-form-item label="密码" prop="password">
		    	<i class="el-icon-edit Cone"> | </i><el-input type="password" v-model="ruleForm2.password" autocomplete="off" class="elinp"  placeholder=" 密码"></el-input>
		  	</el-form-item>
		  	<el-form-item>
		    	<i class="el-icon-picture-outline Cone"> | </i><el-input class="elinp" readonly placeholder=" 验证码"></el-input>
		  	</el-form-item>
			<span class="demonstration"  @click="dialog_art_add = true">没有账号?点此注册</span>
		  	<el-form-item>
		    	<el-button type="primary" @click="submitForm('ruleForm2')" id="submit" native-type="submit">登 录</el-button>
		  	</el-form-item>
		  </el-form>
		  
	  </div>
  </div>

  <el-dialog title="注册用户" :visible.sync="dialog_art_add">
        <el-form :model="userform" :rules="rules1" ref="userform" status-icon>
            <el-form-item label="用户名" prop="username">
                <el-input v-model="userform.username" autocomplete="off" placeholder="用户名"></el-input>
            </el-form-item>
            <el-form-item label="密码" prop="password">
              <el-input type="password" v-model="userform.password" autocomplete="off" placeholder="密码"></el-input>
            </el-form-item>
            <el-form-item label="确认密码" prop="checkPass">
              <el-input type="password" v-model="userform.checkPass" autocomplete="off" placeholder="确认密码"></el-input>
            </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
            <el-button @click="clearForm('userform')">取 消</el-button>
            <el-button type="primary" @click="userAdd('userform')">确 定</el-button>
        </div>
  </el-dialog>
</div>




</template>



<script>
export default {
	 data() {
    var username = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入用户名"));
      } else {
        callback();
      }
    };
    var password = (rule, value, callback) => {
      if (value === "") {
        callback(new Error("请输入密码"));
      } else {
        callback();
      }
    };

    var validatePass = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请输入密码'));
        } else {
          if (this.userform.checkPass !== '') {
            this.$refs.userform.validateField('checkPass');
          }
          callback();
        }
      };
      var validatePass2 = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('请再次输入密码'));
        } else if (value !== this.userform.password) {
          callback(new Error('两次输入密码不一致!'));
        } else {
          callback();
        }
      };
    return {
      ruleForm2: {
        username: "",
        password: ""
      },
      rules2: {
        username: [{ validator: username, trigger: "blur" }],
        password: [{ validator: password, trigger: "blur" }]
      },
      rules1: {
          password: [
            { validator: validatePass, trigger: 'blur' }
          ],
          checkPass: [
            { validator: validatePass2, trigger: 'blur' }
          ],
          username: [
            { validator: username, trigger: 'blur' }
          ]
        },
      userform: {},
      dialogTableVisible: false,
      dialog_art_add: false, /** 文章添加弹窗开关 */
    };
  },
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate(valid => {
        if (valid) {
          var data = this.$qs.stringify({
            username: this.ruleForm2.username,
            password: this.ruleForm2.password,
            type: "login",
          });

          this.$axios({data})
            .then(callback => {
            var { data, meta } = callback.data;
            if (meta.status === 200) {
              /* 将userinfo写入localStorage */
              
              localStorage.setItem('userinfo',JSON.stringify(data));
              this.$message({ message: meta.msg + '亲爱的' + data.username, type: "success" });
              this.$router.push("/");
            } else {
              this.$message.error(meta.msg);
            }
          });
            } else {
              return false;
            }
      });
    },
    userAdd(formName) {
            this.$refs[formName].validate(valid => {
                if (valid) {
                    var data = this.$qs.stringify({
                        username: this.userform.username,
                        password: this.userform.password,
                        type: "useradd",
                    });
                    this.$axios({data})
                        .then(back_data => {
                            var { data, meta } = back_data.data;
                            if (meta.status === 201) {
                                this.$message({ message: meta.msg, type: "success" });
                                this.addform = {};
                                this.$refs[formName].resetFields();
                                this.dialog_art_add = false;
                            }else{
                                this.$message.error(meta.msg);
                            }
                        });
                } else {
                    // this.$message({ message: '出错啦，好嗨呦！', type: "error" });
                    return false;
                }
            });
  },
  /** 清空弹窗表单数据 */
        clearForm(formName) {
            this.userform = {};
            this.$refs[formName].resetFields();
            this.dialog_art_add = false;
        },
  }
}
</script>

<style>
.bodyy{
  height: 100%;
  width: 100%;
  background-image: url(/static/img/banner.jpg);
  overflow:hidden;
  position:fixed;
  top: 0;
  left: 0;
  width:100%;
  height:100%;
  min-width: 1000px;
  zoom: 1;
  background-repeat: no-repeat;
  background-size: cover;
  -webkit-background-size: cover;
  -o-background-size: cover;
  background-position: center 0;
}
.main {
  margin-top: 6%;
  background-size: 100% 100%; 
  background-image: url(/static/img/huabian.png);
  background-repeat: no-repeat;
  background-size: 73% 100%;
  background-position: 50% 40%;
}

.main .center {
  width: 27%;
  height: 28em;
  padding: 5.5% 0% 0% 8%;
  background-image: url(/static/img/center.png);
  background-size: 170% 150%;
  background-position: 53% 49%;
  opacity: 1;
  margin: auto;
  font-size: 14px;
  color: #cccccc;
}
.demo-ruleForm {
  width: 65%;
  margin-top: 10%;
}
.elinp input {
  width: 100%;
  height: 3em;
  background-color: rgba(224, 227, 232, 0.11);
  border-radius: 5px;
  opacity: 1;
  outline-style: none;
  border: 1px solid rgba(239, 239, 239, 0.15);
  font-size: 1em;
  font-family: "微软雅黑";
  padding-left: 40px;
}
.el-form-item__label {
  padding: 0;
  display: none;
}

.Cone {
  position: absolute;
  left: 5px;
  line-height: 40px!important;;
  font-size: 1.5em;
}

#submit {
  width: 100%;
  line-height: 1.5em;
  font-size: 1em;
  background-color: rgba(0, 133, 159, 0.96);
  font-weight: bolder;
  font-family: "微软雅黑";
  opacity: 1;
  margin: 0 auto;
  border: none;
}
#submit:active {
  background-color: rgba(0, 113, 136, 0.96);
  top: 5px;
  box-shadow: 0px 0px 5px rgba(241, 237, 239, 0.35);
}

#submit:hover {
  background-color: rgba(0, 140, 150, 0.96);
  top: 5px;
  box-shadow: 4px -2px 5px rgba(241, 237, 239, 0.35);
}

.demonstration{
	display: block;
	margin-top: -15px;
	margin-bottom: 5px;
	text-align: right;
  color: #fff;
}
.demonstration:hover{
  color: #eee;
  cursor: pointer;
}

</style>