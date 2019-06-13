## swoft-swagger

### 说明

   `swoft` 的 `swagger` 组件， 本组件依赖[swagger-php](https://github.com/zircote/swagger-php) 3.0及以上版本, 需使用 `openapi` 规范编写接口文档

### 安装

1. composer command
```shell
     composer require wp-breeder/swoft-swagger:dev-master
```

> swoft-1.x 请查看 [1.0.0](https://github.com/wp-breeder/swoft-swagger/tree/1.x) 版本文档

2. `AUTO_SWAGGER` 用来控制该组件是否启用 

```shell
    # 在 .env 中添加 auto swagger 开启swagger组件
    AUTO_SWAGGER=true
```


### 用法

1. 发布 `Swagger UI` 的静态资源到项目的 `public` 目录

> 注意：因为在实际开发中可能会有多个 swoft 服务，所以推荐单独部署 swagger ui, 通过修改 json 地址的方式渲染接口文档

```shell
php bin/swoft swagger:publish swoft/swagger
```
2. `openapi json` 地址: `http://{ip}:{host}/__swagger/api-json`, 该接口会动态生成最新接口 `json`, 方便 `Swagger UI` 渲染接口文档
> 注意：本项目会占用 /__swagger/api-json 和 /__swagger/docs 两个路由

3. 在项目的除了`/project/to/path/vendor`,`/project/to/path/test`,`/project/to/path/tests`的任何地方开始编写接口文档，即可生成 `openapi json`, 如需在本项目访问接口文档(已发布静态资源), 则访问 `http://{ip}:{host}/swagger/docs`

### 更多关于 `openapi` 的文档或示例

- [https://www.openapis.org/](https://www.openapis.org/)
- [https://swagger.io/docs/](https://swagger.io/docs/)
- [https://github.com/zircote/swagger-php/tree/master/Examples](https://github.com/zircote/swagger-php/tree/master/Examples)

### LICENSE
The Component is open-sourced software licensed under the [Apache license](https://github.com/wp-breeder/swoft-swagger/blob/master/LICENSE).
