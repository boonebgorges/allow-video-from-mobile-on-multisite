WordPress Multisite does not give regular authors the ability to post unfiltered HTML. This wreaks havoc with the iOS WP apps, which use <video>, <object>, and <embed> elements to display uploaded video. One solution is to map the unfiltered_html capability to the Author role. But this is a very general solution to a very specific issue, and could introduce undesired security problems.

This plugin uses a more fine-grained technique: when a new post comes in through XML-RPC (the protocol used for mobile apps), only the tags required for the iOS video markup are whitelisted.

Only use this if you know what you're doing.
