var _tbEmbedArgs = _tbEmbedArgs || [];
(function () {
    var u =  "https://widget.textback.io/widget";
    _tbEmbedArgs.push(["widgetId", "7ddd6a62-c29f-4428-bf5e-15d61b00a032"]);
    _tbEmbedArgs.push(["baseUrl", u]);

    var d = document, g = d.createElement("script"), s = d.getElementsByTagName("script")[0];
    g.type = "text/javascript";
    g.charset = "utf-8";
    g.defer = true;
    g.async = true;
    g.src = u + "/widget.js";
    s.parentNode.insertBefore(g, s);
})();
