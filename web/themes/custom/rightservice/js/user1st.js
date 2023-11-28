var _u1stSettings = {};
var isActive = ((/u1stIsActive=1/).test(document.cookie));
var script = document.createElement("script");
script.id = "User1st_Loader";
script.src = "https://fecdn.user1st.info/Loader/head";
(!isActive) && (script.async = 'true');
var documentPosition = document.head || document.documentElement;
documentPosition.insertAdjacentElement("afterbegin", script);
