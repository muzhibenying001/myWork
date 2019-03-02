<template>
<div id="content">
    <el-row :gutter="20">
        <el-col :span="6">
            <div class="grid-content bg-purple">
                <el-card shadow="always">
                    <div style="float:left;">
                        <i class="el-icon-phone-outline layi"></i>
                    </div>
                    <div style="float:right;">今日来访<br><br>{{today}}</div>
                    <div style="clear:both;"></div>
                </el-card>
            </div>
        </el-col>
        <el-col :span="6">
            <div class="grid-content bg-purple">
                <el-card shadow="always">
                    <div style="float:left;">
                        <i class="el-icon-tickets layi"></i>
                    </div>
                    <div style="float:right;">文章数量<br><br>{{artcount}}</div>
                    <div style="clear:both;"></div>
                </el-card>
            </div>
        </el-col>
        <el-col :span="6">
            <div class="grid-content bg-purple">
                <el-card shadow="always">
                    <div style="float:left;">
                        <span class="iconfont layi">&#xeb97;</span>
                        <!-- <i class="el-icon-service layi" style="color:#E46E86;"></i> -->
                    </div>
                    <div style="float:right;">昨日流量<br><br>{{lastday}}</div>
                    <div style="clear:both;"></div>
                </el-card>
            </div>
        </el-col>
        <el-col :span="6">
            <div class="grid-content bg-purple">
                <el-card shadow="always">
                    <div style="float:left;">
                        <i class="el-icon-edit-outline layi"></i>
                    </div>
                    <div style="float:right;">用户数量<br><br>{{person}}</div>
                    <div style="clear:both;"></div>
                </el-card>
            </div>
        </el-col>
    </el-row>
    <el-row :gutter="20">
        <el-col :span="8">
            <div class="grid-content bg-purple">
                <el-card shadow="always">
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-goods layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-time layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i><span class="iconfont layi">&#xeb9f;</span></i>
                                    <!-- <i class="el-icon-view layi"></i> -->
                                </el-card>
                            </div>
                        </el-col>
                    </el-row>
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-picture-outline layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-share layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-upload layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                    </el-row>
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-date layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-news layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <div class="grid-content bg-purple">
                                <el-card shadow="hover">
                                    <i class="el-icon-menu layi"></i>
                                </el-card>
                            </div>
                        </el-col>
                    </el-row>
                </el-card>
            </div>
        </el-col>
        <el-col :span="16">
            <div class="grid-content bg-purple">
                <el-card shadow="always">
                    <el-alert title="七天内登陆人数统计" type="info"></el-alert>
                    <div id="charts" style="width: 800px;height:360px;"></div>
                </el-card>
            </div>
        </el-col>
    </el-row>
    
</div>
    
</template>


<script>
import echarts from "echarts";

export default {
    data() {
      return {
        today: 0,
        lastday: 0,
        artcount: 0,
        person: 0
      }
    },
    mounted() {
        var data = this.$qs.stringify({
            type: "info",
        });
         this.$axios({data})
                .then(back_data => {
                    var { data, meta } = back_data.data;
                    
                    if (meta.status === 200) {
                        var { data, meta } = back_data.data;
                        this.today = data.logincount;
                        this.artcount = data.artcount;
                        this.lastday = data.last;
                        this.person = data.person;
                        var char = {
                            'xAxis':[{type:'category',data:data.charts.xAxis.split(',')}],
                            'series':[{type:'line',data:data.charts.series.split(',')}]
                        };
                        myChart.setOption(char);
                }
        });
        
        var myChart = echarts.init(document.getElementById("charts"));
        // 指定图表的配置项和数据
        var option = {
            tooltip: {},
            source: [],
            legend: [],
            xAxis: [],
            yAxis:[
                {type:'value'}
            ],
            series:[]            
        };

        
        
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);

    }
}
</script>


<style>
.text {
    font-size: 14px;
  }

  .item {
    margin-bottom: 18px;
  }


#content .el-card{
      height: 100%;
      margin: 30px 0;
  }

.layi{
    font-size: 30px;
    color: #2BC6CA;
    line-height: 30px;
}
</style>
