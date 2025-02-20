%% -*- tab-width: 4;erlang-indent-level: 4;indent-tabs-mode: nil -*-
%% ex: ts=4 sw=4 et
{application, lager,
 [
  {description, "Erlang logging framework"},
  {vsn, "3.4.1"},
  {modules, []},
  {applications, [
                  kernel,
                  stdlib,
                  goldrush
                 ]},
  {registered, [lager_sup, lager_event, lager_crash_log, lager_handler_watcher_sup]},
  {mod, {lager_app, []}},
  {env, [
            %% Note: application:start(lager) overwrites previously defined environment variables
            %%       thus declaration of default handlers is done at lager_app.erl

            %% What colors to use with what log levels
            {colored,false},
            {colors, [
              {debug,     "\e[0;38m" },
              {info,      "\e[1;37m" },
              {notice,    "\e[1;36m" },
              {warning,   "\e[1;33m" },
              {error,     "\e[1;31m" },
              {critical,  "\e[1;35m" },
              {alert,     "\e[1;44m" },
              {emergency, "\e[1;41m" }

            ]},

            %% Whether to write a crash log, and where. False means no crash logger.
            {crash_log, "lager_log/crash.log"},
            %% Maximum size in bytes of events in the crash log - defaults to 65536
            {crash_log_msg_size, 65536},
            %% Maximum size of the crash log in bytes, before its rotated, set
            %% to 0 to disable rotation - default is 0
            {crash_log_size, 10485760},
            %% What time to rotate the crash log - default is no time
            %% rotation. See the README for a description of this format.
            {crash_log_date, "$D0"},
            %% Number of rotated crash logs to keep, 0 means keep only the
            %% current one - default is 0
            {crash_log_count, 10},
            %% Whether to redirect error_logger messages into the default lager_event sink - defaults to true
            {error_logger_redirect, true},
            %% How many messages per second to allow from error_logger before we start dropping them
            {error_logger_hwm, 50},
            %% How big the gen_event mailbox can get before it is
            %% switched into sync mode.  This value only applies to
            %% the default sink; extra sinks can supply their own.
            {async_threshold, 20},
            %% Switch back to async mode, when gen_event mailbox size
            %% decrease from `async_threshold' to async_threshold -
            %% async_threshold_window. This value only applies to the
            %% default sink; extra sinks can supply their own.
            {async_threshold_window, 5}
        ]},
  {maintainers, ["Mark Allen", "Andrew Thompson"]},
  {licenses, ["Apache 2"]},
  {links, [{"Github", "https://github.com/erlang-lager/lager"}]}
 ]}.
