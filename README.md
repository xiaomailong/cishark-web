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
        
5. 打开浏览器进行测试，[http://localhost/cishark](http://localhost/cishark)