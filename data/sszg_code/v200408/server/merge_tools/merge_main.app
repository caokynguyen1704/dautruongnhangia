%% 应用程序信息文件
{application, merge_main, [
        {description, "merge server"}
        ,{vsn, "0.1"}
        ,{modules, [merge_main]}
        ,{registered, []}
        ,{applications, [kernel, stdlib]}
        ,{mod, {merge_main, []}}
        ,{start_phases, []}
        ,{env, [
                {merge_type, zone} %% zone | center
                %% 服务器信息
                ,{merge_target, [
                        "127.0.0.1"             %% 地址
                        ,3306                   %% 端口号
                        ,"root"                 %% 用户名
                        ,"mx52.cn"               %% 密码
                        ,"sszg_local_1_2"         %% 库名
                        ,utf8                   %% 编码
                        ,10                     %% 最小连接数
                        ,30                     %% 最大连接数
                    ]
                }
                ,{merge_src_list, [
                        {
                            "local"              %% 平台
                            ,1
                            ,"127.0.0.1"        %% 地址
                            ,3306               %% 端口号
                            ,"root"             %% 用户名
                            ,"mx52.cn"               %% 密码
                            ,"sszg_local_1"      %% 库名
                            ,utf8               %% 编码
                            ,10                 %% 最小连接数
                            ,30                 %% 最大连接数
                            ,1000               %% 阵营值
                        }
                        ,{
                            "local"              %% 平台
                            ,2
                            ,"127.0.0.1"        %% 地址
                            ,3306               %% 端口号
                            ,"root"             %% 用户名
                            ,"mx52.cn"               %% 密码
                            ,"sszg_local_2"      %% 库名
                            ,utf8               %% 编码
                            ,10                 %% 最小连接数
                            ,30                 %% 最大连接数
                            ,1001               %% 阵营值
                        }
                    ]
                }
            ]
        }
    ]
}.
