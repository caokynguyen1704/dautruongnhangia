%% -*- tab-width: 4;erlang-indent-level: 4;indent-tabs-mode: nil -*-
%% ex: ts=4 sw=4 et
{application, goldrush, [
    {description, "Erlang event stream processor"},
    {vsn, "0.1.9"},
    {registered, []},
    {applications, [kernel, stdlib, syntax_tools, compiler]},
    {mod, {gr_app, []}},
    {env, []}
]}.
