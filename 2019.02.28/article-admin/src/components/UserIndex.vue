<template>
<div>
    <el-card shadow="always" class="el-card bread">
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>用户管理</el-breadcrumb-item>
            <el-breadcrumb-item>用户列表</el-breadcrumb-item>
        </el-breadcrumb>
    </el-card>
    <el-table :data="tableData3" style="width: 90%;margin: 0 auto;">
        <el-table-column>
            <template slot="header" slot-scope="scope">
                <el-button icon="el-icon-circle-plus-outline" plain disabled id="add">添加用户</el-button>
            </template>
            <el-table-column prop="id" label="ID" width="50"></el-table-column>
            <el-table-column prop="username" label="用户名" width="230"></el-table-column>
            <el-table-column prop="count" label="文章数量" width="100"></el-table-column>
            <el-table-column prop="" label="操作" width="200">
                <template slot-scope="scope">
                    <el-button type="primary" size="mini" plain><i class="el-icon-edit"></i> 修改</el-button>
                    <el-button type="danger" size="mini" plain><i class="el-icon-delete"></i> 删除</el-button>
                </template>
            </el-table-column>
        </el-table-column>
        
    </el-table>
</div>
</template>

<script>

export default {
    data(){
        return{
            tableData3: [], /** 文章列表数据 */
        }
    },
    mounted(){
        this.getArtData();        
    },
    methods: {
        /** 获取文章总数据 */
        getArtData() {
            var data = this.$qs.stringify({
                        type: "user",
                    });

            this.$axios({data})
                .then(back_data => {
                    var { data, meta } = back_data.data;
                    console.log(data);
                    if (meta.status === 200) {
                        var { data, meta } = back_data.data;
                        this.tableData3 = data.user;
                }
            });
        }
    }
}
</script>

<style scoped>
.bread {
  margin: 10px;
}
.sou {
  float: right;
  width: 200px;
}

#add{
    margin: 0 15px;
}

.block {
  text-align: left;
  margin: 10px 80px;
}
</style>
