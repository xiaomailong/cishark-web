# cishark-web
计算机联锁日志查询web平台  
[github主页](https://github.com/lisency/cishark-web)


## 使用框架
THINKPHP3.0

## 安装与部署
1. 安装apache2.0，PHP5.3，Mysql5
2. 进入apache网站根目录

        $cd /www

3. 从github上克隆

        $git clone https://github.com/lisency/cishark-web.git cishark
        
4. 配置mysql数据库，数据库配置在./log/Conf/config.php当中

        $vi ./log/Conf/config.php
        
5. 若数据库未创建请创建数据库，使用sqlyog等工具执行`sqlbackup`目录下的sql脚本。

    1. 执行`create*.sql`。依次执行`create_datadict.sql`，`create_log.sql`，`create_session.sql`，`create_station.sql`脚本。
    2. 执行`fun*.sql`。依次执行`fun_get_cpu_state_name.sql`，`fun_get_log_type_id.sql`，`fun_get_series_state_name.sql`脚本。
    3. 执行`view*.sql`。依次执行`view_log_info.sql`，`view_session.sql`。


5. 打开浏览器进行测试，[http://localhost/cishark](http://localhost/cishark)