#!/bin/bash
ulimit -SHn 102400
erl -kernel inet_dist_listen_min 40001 -kernel inet_dist_listen_max 40100 +P 204800 +K true -smp enable -pa /data/sszg_code/v200408/server/ebin1 -name merge@jstm.merge -s merge_main start -extra immediacy
