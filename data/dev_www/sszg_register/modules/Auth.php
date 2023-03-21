<?php
/**----------------------------------------------------+
 * 服务端启动处理
 * @author whjing2012@gmail.com
 +-----------------------------------------------------*/
class Auth extends App
{
    const CDEBUG = true;
    const KEY_1 = "asdfnqewr12ndfasfq3omasdf;ashfas;fqwoerqwefbsdf";
    const KEY_2 = "asdfnqewr12ndfasfq3omasdf;ashfas;fqwoerqasfasf";
    public function run(){
        if (!Auth::CDEBUG) exit("fail");
        $p = array_merge($_POST, $_GET);
        Api::log($p, 'server_run_log', 'request');
        $username = Api::getVar('username'); //
        $password = Api::getVar('password'); //
        $sign = Api::getVar('sign'); //
        $time = Api::getVar('time'); //
        $tick = md5("${username}${password}${time}".Auth::KEY_1);
	if($sign != $tick) exit("error");
	$users = [];
	$users['whjing'] = array('pass'=>'whjing', 'conn_max'=>0);
	$users['sy0022'] = array('pass'=>'AAAAB3NzaC1yc2EAAAABJQAAAIEAltkFe/V4VE3MUw/218TQnsnVbGAnRe7Rr7AdDP7bdIe0fV5caQnVjXsuhnYni9zu9d7WDDcZyYnbSExN3yGNnLfr5oT+4NblWsTUlmihXOE6vn2XOYoIeanPBI/65KcFW/p4okc83FUysdfbz2pjwHcNY8PdJ4RxJxg5wSci6HU=', 'conn_max'=>0);

	$users['sy0728'] = array('pass'=>'AAAAB3NzaC1yc2EAAAADAQABAAABgQDFJgOiQDMQp8AsU249to0sACM1FLwfPRWw/lrxlFf51qqZKahb00aqWzOSop80Wz4t+bTMuWRoUejuuZZ2ExqffIPcuO/xCc+xVi6RM2mNSumSukEVw6tOI31PnjJk/XBOVmqaCRCC+w8f8DWXvJiZnx0xH+kZ1THIIfa2kWQc2+q9oyeetbMt9LMWfkqSNB6yfuK8/SrtG/nbQAzmaIYI2rS1s+1i2KtXaFwgb1FOC9c/q7O5W6b3GdiQND7h6tfQ6uNaFQCgDv13FY6dIPo5we0T9ynBJEgu9mP+rG9jndDlQKyp5hqJV7wgQdV0HV+DpzAyPf8pLY00Xztgp0epWudaBUFsiFkjfX/KayQe5trj2Uw+eN+ZOCgJkoaLkbPRVWYqzVRAbgzE58paAEsdfJNs3JqtrRk+9Dnros3zUWkSPOT6akxg2NVLabOsnnHKodMOOR6O7VhMzj5cj7SiQr5AE046OIUORAE9RmklUYlmm78Xl1ozWI6W8/O/yo8=', 'conn_max'=>10000); // 何易晟
	$users['sy0623'] = array('pass'=>'AAAAB3NzaC1yc2EAAAADAQABAAABAQDl510afnA4MvtVjRfPYknDn8r0V6SguQMmmGhbOZoDA8eJhL8BJi1/lkjQomQy/AMM21/kSD9w6tqDzMWv5X2H1mrt6qwlfXtBwqKxG7PCq693tqvJ5sk7/ZD6wNyewi82eyqAKWFwsAkvmZVnE6qc+WQXRsnm3lIp6BwKhD8eTlIhtLuUSFlB0TMUff7YeRUt/FiWmh7S9LHtPaJgsLSKZ+Ct5S8VlFdDd/6IlSGrnUzZclMm3RJDeZXqz4Re90PJ9yJDwWlWquicvOIKj5oP14T82lDju/7L+g5BxY8RepjOsSdgu1yqkTMlr0V1BiZ2veECcpYgfbgLFvkg5UTJ', 'conn_max'=>10000); // 吴锦汉
	$users['sy0484'] = array('pass'=>'AAAAB3NzaC1yc2EAAAADAQABAAABAQCy3MuIlbJC6oytNnUHZm/veMeSQmznSiJXkpFJhlm5TX1ozY3Aac2QybmFaLA9vomG+ZigdTCAEXugiFQVk+UXF8tGdu/FiWcHmaHUAqMAFke4WVLV2WIhvv8VLiqy+IHU/32UcqB889qNIgFbSI+8ArpypzdaUDUBONkf+fvKYEAGJt2qJixI1A74WUyBs4jwImEqJRGDJe1U7TvGOcyCHD11S5Og6jtIvlzgN6OL9Auyd8nUbt7OdDdLRm4tQTqjoJEPcgL1+qQ+GIeULKbMCM/NI9q5nsCflPGwuzUxtLMZH1XI1gdjUZbKGS6QclTUGyh4oCSHkpvvQtp2NlVL', 'conn_max'=>10000); // 林国辉
	$users['sy0417'] = array('pass'=>'AAAAB3NzaC1yc2EAAAADAQABAAACAQDFw2WOgZVAqoH3yYxkRk7fVksDEYHOicx25fFLJ87x8SBHJI27NMroSPLTc7p6mm4Z7mMuWsRz/2uijSWUjfp6DMtxvzWhIi32D+ghBFB3OQtf6HQuA3U8TyIKcts0fymHutVVKyF2ZDAZHdMBHs14hXJ55xp0ivJXPRA0y0QyC9v9N/VEWpKvg6TnZZ2auqhjRppNWTd7+jtn3NOoZimUyyNbUL3/LnzJWad6kWenufkryFK34siUr2CzJB9uoX8p07N1yp5dSysxNeM/6jAIMNQZvDjkqLmlOI++N6VWOAHDq+qVOG08E9VTYAnQurxUui5afvELoDHXgaium55qwlLLKCXC6APpSC0kKcWH2Gy/6qRVuXdjReAPuQUddCcV0f3Pz2y6mFVWjd3kZvrepW9Q0kX7PIr8qIIoVoL5e1xvfyJCBUGflx9hrX35gJDqaueqO7PSRig7akjbyfGLs/XKlszFsY0te2ydgqg3DwAoZmVLGDTIM6KjpARDW79X2VoRGJE059E0rsO2K/PS7wy1/c9tggm1Nobfb3fMGE65m3gX9tRvlnu8KdNeGuTYl6PqH0B/7kfbhcYuLDywI7/WVg4JR/GaG0Faf8n0RtfrQfIwDnmw4qLcNPygTIJcLYS9aO3GfXMju01pE2c50uWOyCf3apV09VxCbssVHw==', 'conn_max'=>10000); // 颜子乔
	$users['sy0229'] = array('pass'=>'AAAAB3NzaC1yc2EAAAADAQABAAABAQDMq71LOqijQE+o0gQ3j/lW5Xvv0Q/GIAMpINvNtAIK8EqjRnMllbvm4SpmW5uDQMCWSr/2F9wiFMSQpiDIuMlcP9Jv9U34Jvc/0RJJgoj7MnxEeS0tEPTLiBHQYt6BrTt7xJRnCYIf1NP0owvXS8xR6J2pgA32kBqRAjQ+sTQtlYZbXM4+7jw/h6RcK1vVuKVthb71qKXFZnvFPafbowD9s9RZtp9/3usQPpqeq6t3EsG+E0EpnlupCDVUyMgCPJKDxnH7mqnELag944fR6Y2+fq69hjVMO2qL8r66CY5GdPDG22WUSkLOCt57GLLR0Cj8iU9dFiRqb2A7K9/vyfRP', 'conn_max'=>10000); // 刘锋林
	$users['sy0818'] = array('pass'=>'AAAAB3NzaC1yc2EAAAADAQABAAABgQDAIgg5HRv4hCVUbzXFF8+cr9cRD23x3cTZNWnKmqOyiFLRwAFe9guassLkowfy5CjidRH8B27KXAPr00ZkCgXW1oQJR0p2cQa0kCWgVDekRD+BC/M2AkBF2rvIEbaCSx0+SKMliMyR+v2bI+QzYSnHP2l4+Jw9Px5G1mQAajsSwa3nvVjlRZ3CwruB/eNCKFkD3vC5Vh9wdG7hROf7uOuxTgQoG0n/p+H6cFq1x7hrOpJ7QQXk3nBoY8c+Hq3SHI9K4e5T9y4EPRrClMmgAV7ZsBpPQmc5WF7YXnurQEhbVz5nmZiJ6bK5vFktQmXqtcn3G7oVgKDvXnCnabrh7P+B+s69Qwdziw6y0PQAGbRaVkXE1t4SLyIAy2Y+MZ3fms6FUjiQMeWzoNda0+OIxbIb9gkjxd/ZnIgKR5f5QJG2Y0VVkw0/I29oKoaipvXckJ0EsIzdpNUgqWMUD+8Tn6f52HicBE7Ui4E/wQbWH70xBvDyBsOacV8aJD7h0avx6q0=', 'conn_max'=>10000); // 黄早艺

	$users['dldldev'] = array('pass'=>'AAAAB3NzaC1yc2EAAAABIwAAAQEA3EpgMDIzp6s3WJYqJQYgtotqg78zH7nEwJZv6u+/hI+Bkf5NZ2FfBBpt2MICVm3Ei6epWQmPMzLmeZPOOMM6+mopNltKHRqPAEW7YBtJgSxZ43D+HuL5/F7rhHMZ4nR9Se5C33PVJ/mx6SZFIqPdm4sbZCZVcZabZDVrxAn4LLBRE6Wc6egJU6fiH81u7w3yzg/g5Bh+6rAY3xbltfEZcLidS22Q8NnRUdccQApal4MklpoplCSbEi4+WqejYe62GsazKVT35HeO96cPoC/+znvrNWNytN3YU3gNdH5WUpOM5UIvLlV6D3H3iQWG5ASHZ/k289P3iTuelz7vij7+9Q==', 'conn_max'=>10000); // 斗罗测试服
	$users['sszgdev'] = array('pass'=>'AAAAB3NzaC1yc2EAAAABIwAAAQEA3EpgMDIzp6s3WJYqJQYgtotqg78zH7nEwJZv6u+/hI+Bkf5NZ2FfBBpt2MICVm3Ei6epWQmPMzLmeZPOOMM6+mopNltKHRqPAEW7YBtJgSxZ43D+HuL5/F7rhHMZ4nR9Se5C33PVJ/mx6SZFIqPdm4sbZCZVcZabZDVrxAn4LLBRE6Wc6egJU6fiH81u7w3yzg/g5Bh+6rAY3xbltfEZcLidS22Q8NnRUdccQApal4MklpoplCSbEi4+WqejYe62GsazKVT35HeO96cPoC/+znvrNWNytN3YU3gNdH5WUpOM5UIvLlV6D3H3iQWG5ASHZ/k289P3iTuelz7vij7+9Q==', 'conn_max'=>10000); // 闪烁测试服

	if(!isset($users[$username]) || md5($users[$username]['pass']) != $password) exit("error");
	$conn_max = $users[$username]['conn_max'];
	$password = $users[$username]['pass'];
        $tick = md5("${username}${password}${time}${conn_max}".Auth::KEY_2);
        exit( "[{success,true},{conn,$conn_max},{sign,\"$tick\"}]" );
    }
}
