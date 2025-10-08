

fetch('/aboutUs',
     {method: "POST",
headers: {"Content-Type": "application/json","X-CSRF-TOKEN":document.head.firstElementChild.content},
body: '{"username":"example2"}',
     }
)