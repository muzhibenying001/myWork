<template>
<el-container>
    <el-aside width="200px" class="height100" >
        <el-card shadow="never" id="logo" body-style="background:#545c64;color:rgba(255,255,255,.8);border:none">
            L-uo.ying.admin
        </el-card>
        <el-menu default-active="/" class="el-menu-vertical-demo height100" 
         :router="true" :default-openeds="openeds"
        background-color="#545c64"
        text-color="#fff" active-text-color="#E1B2AF">
            <el-submenu v-for="top in menus" :index="top.order+''" :key="top.id" >
                <template slot="title">
                    <span class="iconfont" v-html="top.icon"></span>&emsp;
                    <!-- <i class="el-icon-location"></i> -->
                    <span>{{top.authName}}</span>
                </template>
                <el-menu-item-group>
                    <el-menu-item v-for="second in top.children" :index="top.order + '' + second.path" :key="second.id">
                        <span class="iconfont" v-html="second.icon"></span>&emsp;{{second.authName}}</el-menu-item>
                </el-menu-item-group>
            </el-submenu>
        </el-menu>
    </el-aside>
    <el-container>
    <el-header>
        <el-card shadow="always" body-style="text-align:right">
            <i class="el-icon-d-arrow-left info_icon" style="float:left"></i>
            <i class="el-icon-location-outline info_icon" style="float:left"></i>
            <i class="el-icon-search info_icon" style="float:left"></i>
            <el-input placeholder="搜索" v-model="input10" clearable size="mini" suffix-icon="el-icon-edit-outline" style="width:150px;float:left;margin-top:-5px;"></el-input>
            <i class="el-icon-bell info_icon"></i>
            <i class="el-icon-message info_icon"></i>
            <i class="el-icon-refresh info_icon"></i>
            <el-dropdown>
                <span class="el-dropdown-link info_icon">
                {{username}}<i class="el-icon-arrow-down el-icon--right"></i>
                </span>
                <el-dropdown-menu slot="dropdown">
                    
                    <el-dropdown-item divided @click.native="logout" >退出</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
            <i class="el-icon-more info_icon"></i>
        </el-card>
    </el-header>
      
        <el-main>
            <router-view></router-view>
        </el-main>
    </el-container>
</el-container>
</template>

<script>

export default {
    data(){
        return{ 
            menus:[
                {'id':0,'authName':'首页','order':'','icon':'&#xe621;','children':[{'id':0,'authName':'控制台','path':'/','icon':'&#xeba2;'}]},
                {'id':1,'authName':'文章管理','order':'1','icon':'&#xe6fb;','children':[{'id':2,'authName':'文章列表','path':'artindex','icon':'&#xeba1;'}]},
                {'id':3,'authName':'会员管理','order':'2','icon':'&#xeb9a;','children':[{'id':4,'authName':'会员列表','path':'userindex','icon':'&#xe70c;'}]}
            ],
            input10:'',
            openeds:['','1','2'],
            username: JSON.parse(localStorage.getItem("userinfo")).username
        }
    },
    mounted(){
        var token = localStorage.getItem('userinfo');
        if( !token ){
            this.$message({message:'请先登陆',type:'success'});
            this.$router.push('/login');
            return;
        }
        
    },
    methods: {
      /* 退出功能 */
      logout(){
          localStorage.removeItem('userinfo');
          this.$message({message:'成功退出',type:'success'});
          this.$router.push('/login');
      }
    }
}
</script>

<style>
.height100{
    height: 100%;
}
.bg-purple-light {
  font-size: 25px;
  color: #409EFF;
}
.el-header {
    /* background-color: #ECF5FF; */
    color: #333;
    text-align: center;
    padding: 0;
}
#logo{
    border: none;
}
.el-card{
    height: 60px;
    line-height: 20px;
}
.el-aside {
    background-color: #D3DCE6;
    color: #333;
    text-align: center;
    line-height: 200px;
    height: 100%;
    overflow: hidden;
}
.el-main {
    color: #333;
    text-align: center;
    padding-top: 0;
    height: 100%;
}
.el-menu-vertical-demo{
    text-align: left;
    height: 100%;
}
.el-container{
    height: 100%;
}

.info_icon{
    margin: 0 20px;
}
</style>
