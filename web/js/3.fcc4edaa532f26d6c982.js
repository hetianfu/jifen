(window.webpackJsonp=window.webpackJsonp||[]).push([[3],{"311":function(A,e,t){"use strict";var n={"Mul":function Mul(A,e){var t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2,n=A.toString(),o=e.toString(),r=void 0,i=void 0;return r=(n.split(".")[1]?n.split(".")[1].length:0)+(o.split(".")[1]?o.split(".")[1].length:0),i=Number(n.replace(".",""))*Number(o.replace(".",""))/Math.pow(10,r),Number(i.toFixed(t))},"Div":function Div(A,e){var t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2,n=A.toString(),o=e.toString(),r=void 0,i=void 0;return r=(o.split(".")[1]?o.split(".")[1].length:0)-(n.split(".")[1]?n.split(".")[1].length:0),i=Number(n.replace(".",""))/Number(o.replace(".",""))*Math.pow(10,r),Number(i.toFixed(t))},"Add":function Add(A,e){var t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2,n=A.toString(),o=e.toString(),r=n.split("."),i=o.split("."),a=2==r.length?r[1]:"",s=2==i.length?i[1]:"",c=Math.max(a.length,s.length),l=Math.pow(10,c),u=Number(((A*l+e*l)/l).toFixed(c));return Number(u.toFixed(t))},"Sub":function Sub(A,e){var t=arguments.length>2&&void 0!==arguments[2]?arguments[2]:2;return n.Add(A,-Number(e),t)}};e.a=n},"313":function(A,e,t){"use strict";t.d(e,"a",(function(){return addGoodsComment})),t.d(e,"d",(function(){return getCommentPage})),t.d(e,"c",(function(){return getCommentInfo})),t.d(e,"e",(function(){return getRecommendPage})),t.d(e,"b",(function(){return getByOrderIdPage}));var n=t(9);function addGoodsComment(A){return n.a.post("/product-replies/add",A)}function getCommentPage(A){return n.a.get("/product-replies/get-page",{"params":A})}function getCommentInfo(A){return n.a.get("/product-replies/get-by-id/"+A)}function getRecommendPage(A){return n.a.get("/product-replies/get-recommend-page",{"params":A})}function getByOrderIdPage(A){return n.a.get("/product-replies/get-by-order-id/"+A)}},"321":function(A,e,t){},"322":function(A,e,t){"use strict";t.d(e,"a",(function(){return v}));var n=t(1),o=t(3),r=t(43),i=t(422),a=(t(396),t(68)),s=t(397),c=t.n(s),l=t(398),u=t.n(l),p=t(399),f=t.n(p),d=t(400),g=t.n(d),h=t(401),m=t.n(h),b=t(306),y=function(){function defineProperties(A,e){for(var t=0;t<e.length;t++){var n=e[t];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(A,n.key,n)}}return function(A,e,t){return e&&defineProperties(A.prototype,e),t&&defineProperties(A,t),A}}();function _classCallCheck(A,e){if(!(A instanceof e))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(A,e){if(!A)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?A:e}var C=function fun(){},v=function(A){function Index(){return _classCallCheck(this,Index),_possibleConstructorReturn(this,(Index.__proto__||Object.getPrototypeOf(Index)).apply(this,arguments))}return function _inherits(A,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);A.prototype=Object.create(e&&e.prototype,{"constructor":{"value":A,"enumerable":!1,"writable":!0,"configurable":!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(A,e):A.__proto__=e)}(Index,A),y(Index,[{"key":"render","value":function render(){var A=this.props,e=A.menus,t=void 0===e?[]:e,o=A.onShare,s={"home":{"name":"首页","icon":c.a,"onClick":a.n},"share":{"name":"分享","icon":g.a,"onClick":o},"wxShare":{"name":"分享","icon":g.a,"openType":"share","onClick":C},"cart":{"name":"购物车","icon":u.a,"onClick":a.h},"user":{"name":"我的","icon":f.a,"onClick":a.F},"service":{"name":"客服","icon":m.a,"onClick":a.B}};return n.l.createElement(r.a,{"className":"goods-foot thin-border__t"},n.l.createElement(r.a,{"className":"goods-foot__left"},t.map((function(A){return n.l.createElement(i.a,{"key":A,"openType":s[A].openType,"className":"goods-foot__menu","onClick":s[A].onClick},n.l.createElement(b.p,{"className":"goods-foot__menu-icon","src":s[A].icon}),n.l.createElement(r.a,{"className":"goods-foot__menu-text"},s[A].name))}))),n.l.createElement(r.a,{"className":"goods-foot__right"},n.l.createElement(r.a,{"className":"goods-foot__btns"},this.props.children)))}}]),Index}(o.a.Component);v.options={"addGlobalClass":!0}},"337":function(A,e,t){"use strict";t.d(e,"a",(function(){return f}));var n=t(4),o=t(616),r=t(393),i=t(3),a=t(5),s=t(312),c=t(313),l=t(8),u=t(2),p=function(){function defineProperties(A,e){for(var t=0;t<e.length;t++){var n=e[t];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(A,n.key,n)}}return function(A,e,t){return e&&defineProperties(A.prototype,e),t&&defineProperties(A,t),A}}();function _classCallCheck(A,e){if(!(A instanceof e))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(A,e){if(!A)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?A:e}var f=function(A){function FyComponent(){_classCallCheck(this,FyComponent);var A=_possibleConstructorReturn(this,(FyComponent.__proto__||Object.getPrototypeOf(FyComponent)).apply(this,arguments)),e=A.$router.params;return A.state={"id":e.id,"opacity":0,"nodeKey":0,"nodeList":[],"initSku":!1,"userInfo":Object(n.b)(a.f)||{},"shareImg":"","goodsInfo":{},"goodsList":[],"comboList":[],"commentRes":{},"goodsImages":[],"showMore":!1,"loadingStatus":!0,"buyList":[],"shareVisible":!1,"score":Object(n.b)(a.f).totalScore},A.rolling=!1,A}return function _inherits(A,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);A.prototype=Object.create(e&&e.prototype,{"constructor":{"value":A,"enumerable":!1,"writable":!0,"configurable":!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(A,e):A.__proto__=e)}(FyComponent,A),p(FyComponent,[{"key":"onShareAppMessage","value":function onShareAppMessage(){return{"title":this.state.goodsInfo.name,"path":Object(l.m)()}}},{"key":"onScroll","value":function onScroll(A){var e=A.scrollTop,t=this.state,n=t.opacity,o=t.nodeKey,r=t.nodeList,i=(e/200).toFixed(2),a=i>=1?1:i;if(n!==a&&this.setState({"opacity":a}),this.rolling)return!1;var s=r.filter((function(A){return e>=A.top})).length-1;o!==s&&this.setState({"nodeKey":s})}},{"key":"getNodeLocation","value":function getNodeLocation(){var A=this,e=0,t=Object(o.a)(),n=[{"name":"商品","id":"#goods-info"},{"name":"拼团","id":"#goods-group"},{"name":"店铺","id":"#goods-shop"},{"name":"评价","id":"#goods-comment"},{"name":"详情","id":"#goods-desc"},{"name":"推荐","id":"#goods-hot"}];t.select("#goods-navbar").boundingClientRect((function(A){var t=A.height;e=t})),n.map((function(A){t.select(A.id).boundingClientRect((function(e){e&&(A.top=e.top)}))})),t.exec((function(){A.setState({"nodeList":n.filter((function(A){return A.top=Math.ceil(A.top-e),!isNaN(A.top)}))})}))}},{"key":"onSwitchNode","value":function onSwitchNode(A,e){var t=this;this.rolling=!0,this.setState({"nodeKey":e},(function(){Object(r.a)({"scrollTop":A.top,"duration":300})})),setTimeout((function(){t.rolling=!1}),400)}},{"key":"getGoodsPage","value":function getGoodsPage(){var A=this,e=Object(l.d)("productRandom");Object(s.a)().then((function(t){Object(s.b)({"page":"1"===e?Object(l.k)():1,"limit":6}).then((function(e){var t=e.data;A.setState({"goodsList":t.list})}))}))}},{"key":"getCommentPage","value":function getCommentPage(){var A=this;Object(c.d)({"page":1,"limit":8}).then((function(e){var t=e.data;t.list=Object(u.A)(t.list),A.setState({"commentRes":t})}))}}]),FyComponent}(i.a.Component);f.options={"addGlobalClass":!0}},"393":function(A,e,t){"use strict";t.d(e,"a",(function(){return i}));var n=t(0),o=void 0,r=void 0,i=function pageScrollTo(A){var e=A.scrollTop,t=A.duration,i=void 0===t?300:t,a=A.success,s=A.fail,c=A.complete;return new Promise((function(A,t){try{if(void 0===e)throw Error('"scrollTop" is required');var l=document.querySelector(".taro-tabbar__panel")||window;o||(o=l===window?function scrollFunc(A){if(void 0===A)return window.pageYOffset;window.scrollTo(0,A)}:function scrollFunc(A){if(void 0===A)return l.scrollTop;l.scrollTop=A});var u=o(),p=e,f=p-u,d=i/17-1,g=Object(n.f)(n.c,d);!function scroll(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:0,t=u+f*g(e);if(o(t),e<d)r&&clearTimeout(r),r=setTimeout((function(){scroll(e+1)}),17);else{var n={"errMsg":"pageScrollTo:ok"};o(p),a&&a(n),c&&c(n),A(n)}}()}catch(A){var h={"errMsg":"pageScrollTo:fail "+A.message};s&&s(h),c&&c(h),t(h)}}))}},"396":function(A,e,t){},"397":function(A,e){A.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADgAAAA8CAMAAAAEyYk6AAAAclBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACa4vOeAAAAJXRSTlMAEeNHyX8wbgj38TyPZqLr1DZUIeDbzrV2TxbArg3Yg15DGpMpG4Hb4AAAAXJJREFUSMftltuSgjAMhisWDAUUOeMR0b7/K26y4lSXraY3jM7430hiP9LwB6bCJuikWm0z4aqg0r/a5m5cE+pBKnHA8i0S4bHPY0LrDZdLFC4/rOnyVODlitfpZqdRZ7hG+yVFy9lrrl3hQu80TthkL2C2YFf0f0sdNV2u7Z7HZELdWx8zWDxPn9i2oHtWF6vn0jooF0lFm/FmTN4mPzQj+Oh5GtxCbi99TeViEC8Ef0YwI4uL6BZy/YKr53sGZiYEkJN0j5ZFmRGUIHz82W2YkBlBX5RYWDgKt1sKNKd1BefogcCyc1cwQugLfsEJwfUbgdD68aBjBnzwkuo7lQEXhIN+ULFngifMHZNBZwwyJrjQujKRR1+mRwV2UJqomBwsJwcPk4Pp54DV24BK6+QpKMfgXGtF+Z0rGNOCht43NzAKtW4EeHQ2yvlg7yPnAXaqNEp5RpgITRSO/sREMBzR3GQOfV2t+JSqO3EnmDEFA/ADjFtfhJOhE1AAAAAASUVORK5CYII="},"398":function(A,e){A.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD0AAAA9CAMAAAApvJHbAAAApVBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABU/knhAAAANnRSTlMAp1gG95lm1FLnYPmuTRYJJe+HeXMP8+vk2svFwoxIQyobsqKflY+APTk3NTAT3rxcbWppPyBe7P9PAAAB7ElEQVRIx92WW3OiQBCFR8WAokaNKN5A4y3GeEk2+f7/T9tlHhZqneYyednK92JR7amC6XO6R30Tr/34DTEMhmt7NeDXTpby9pSEed9S370CdJQtbxsYKHtasLJXd+DBXj2Cjb36BBd7dcOBnj7AekleshZdgPfnJ6Y0rThVD2GfeIfyTHqpZWCWfMGhVpIQbn/VZ/ArhW0Ho/TJhZcq6hlE6VMTniqIH30IMlmD5wrqT3BVSr9aUJ6gaR+UZ2jbB2UC/X+Csi0t7sNEZXmFqSomPWNzUGTM/U2DkkZ21zTyIXlrCMtMBwS6STW493UX5hnjChyTagQzpTEHpdd+MKLF6gB7pbEJyhXq0kEWM4a10MRi3iCUDFTML6gZzFsyKBt4N26Udhn1BU5KYxGUHjgNdcexXFA8WIgbpZAlDJWBOXSL1em/5KCkyG9oCIpA8Rg5gxOUGKdbcUP4TgHATWpGGdyG1EuXIsa1dc6RFqH+V1aHzj4SpsKws/zMT74DUGuYGu0D7PKM5KM53Jci0LznXjkX3g7G96UFXL0NtGR1CF96Nq9MgzTQS0ZutwuvqtGCL9M9uq+CAchJ2EJrP4PQ+FGXpQuuElkP0NQNG99BEyuZ4xQYf5hKcQgMbvk3sXjk9YRSNOqe1U/gN2PHczYoirbdAAAAAElFTkSuQmCC"},"399":function(A,e){A.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADoAAAA6CAMAAADWZboaAAAAb1BMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABt6r1GAAAAJHRSTlMA6QsFJPXf2MHNoX9zUxfRkTMS77SIZ0QtyJqWW05JQDgcO71sDKMmAAACLklEQVRIx5ST266DIBBFEUUQtRcIpjY0Nen6/288D33pSZyx3a+LlQxzMUJSjcE750OsyfySOfCRMH8tPlfecY531udXYnsHyLF21hjb1ZgB7u2xmQLQLB8v26UBwuGXTz1QrPkXW4D+dFBtADfv9M3BqtccwV33wNVB1MwH8NCQmNbDKMERvFzyAr1I2x4WUfVQjZgKXmId9EZJD52AJrho6gUmAb3gqe42vOSCrKZa+UOObNRknLD44HXVQ9rffDjr6hmEG4BGVxuQ29SqZyW3aYWbpt5gFVCBUVNHKALaYNDUATalDZtsblobCwRZDVBEaBuYJThDo+zpBLkTLjLDpE1uAJ/2SPIwqFP/q7budhuEYTAM25BQQiA/KgUEKirou/9rHDC169AI0LM95698ZnvgqRV/zJxKHijoIYFrTys9A/JBO0YGUDb0pikB8Ei7BoU5rgwtTDWHUAMdEGmJGauyVIyZ1BEdYzqJN7IzdFx0zxIskuwe0Vmx8d7E9C+IQrusTK1Ny8zpQtAhce3SK1auqavjva5lbOA2UEf5s7NZXtReGCN8XeSZfdZ5FAyt9jGtxF7b7bhfwksuaIPIL0vcr0c6TFRBQYXCxP0aLCyApKddfQLAireSAXQRHRB1APjVDgnANR1UM5AMPxuLGzqs4dfu6zbKYNvRxGPj+wj+I/Dfp7Glk9rlXI6BexC8MCM5QNNpGnCkAEGnCUCRBNMHGJJuXNEHKr59Ab6rP9aCL2DrAAAAAElFTkSuQmCC"},"400":function(A,e){A.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADoAAAA6CAMAAADWZboaAAAAgVBMVEUAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABtFS1lAAAAKnRSTlMAkTUH91VT8J4aFOms3tjGYONOl3haDcy/s4N/ay7Tcj8npY+KRCERuUpBICVBAAACSklEQVRIx52W69aqIBRFCS1N81J27+t+n+//gAdHJZRxNNY/henYwGJtRZOmWSKFm+4Q993QLjDznFEG7ihjRzQGRm5oJ4Rg74Z6GSRXJ1T0h3BzQ4X04eiGihzI3VBxAl+6oeJWOtINvaalI51QsQ/g0Babysl2UKHi3tKR+zxMKaVRMQbuDdxllaI1qd4fmhx5XjyZ4aKzzbvGkXgzSO2OlPMHVvSi+mA/tjsyOpScH+5sH/bh9HVkFAPBKhJW5cbiTZ1QKkrQrs43R3oDIF2LBt1g+OHIawaEzVa7JpC9TfNK8k+00DqA0HwxAH8iWmkEbPXj8ZfYWwFnM263jYhZYvpyQgxhM2HuS6Z9vfHao2Uwr/VNlO3JP6AnHtr8FJVnYKU3O25f7iUwWt/sl1Y23Rj7coZg+su5xBfjYdmaXAK7qgIf+u2z1TT6BOZtybUPhVG87cJEcjTJt+OR8SaBuadN5UNUi688zAKeyqupc0iMubtavbsiQcuI8AJ8M0TG7/u7Xw5fSLoIj6tQoYb/usLQwryncvCgkiKXjzX1KnTnfx5irJcqH7E/G+uqNHqJYfG+IZA8z/f4SG99hUzUK/03fd+Tl5dH5RqDpargKzpQg5damp/Kj3ZQOmhXmWiVRrWE2qqys3KNqlQL2vsWtGF54WUMFJ6woTp5Tc1BSjUSqMSwodFH3utr3lVkshdWtJjDMKoPpeArsi/sqG/52xqilCrSjlo6qojRPc+C2vq4rsaOLoQN/W9/k5BOLTEVjBuiPrQs5x/xrVOkgKd70gAAAABJRU5ErkJggg=="},"401":function(A,e){A.exports="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADoAAAA6CAYAAADhu0ooAAAFUElEQVRoQ9WbV6hkRRCGv8UMCuYsGBDTgwExrDnAqhhWXUVQMCAK5vAgPogKhlXMCVTEgIkVUcwBzDmt+mBOKCbMPpjwQb6l+3Lse0KfmTmzZwqGCzPd1fV3VXdXujPojhYBdgF2BdZIPq76dfJ5DXgV+LYLkWaMmOlawF7AjsC+wNID8H8bEPSDwEMDzC+dMiqg2wCHAocBy45KuAD2hlEAHhbodsBpwAEV4H4FngHeAH4pfBy+XOGzJbBzzSap3csCr4H2cRigJwIXAUslK38B3BqEEmQbEqyfw4G1SyaeC5zThmEcOwjQlQPAI5IFPVsCvAVQk8OQ5i9/AW+WMHLzBNxqE9sC3Ri4E9g0WXzgnc7YDTV4dsm4vYGHM+YvGNIGqCDnAZsUmA+0u7nCFcZpzoL1b6Tfws3+XA6/XKBlIDXT1Hxz1hxmjBt9UIHBl8D+wFtNTHOAel6eTs7K1cBJTcw7+j0F+w6wO/Bj3Xo5QC8HTikw6fI85u6NZly8fW8EjhkG6GzgvuRM6tb1gR4A9ikIIlABl1KdRstMVpCtrvUOd2R14HXAv5KmqwlrytOoDmh6rffBZFMARydavA44vg3Q1YLbFnfrU2Br4KcONTQo62dDEOH8P4HNgQ9TZlUaTbV5KnDFoJJ0PM/nxps40sXAGTlAU236RqnNfzsWeBj2jwJ7BAY/BK0a705RmUZ1Am4ujDkPOGsYKcYw1wjq0sI6RwafuxaozsAJhUkbAR+MQdhhllgC+KvA4BrA6KoW6EvAtmHE+4Du3yTQ88D2QdCXgZl1QFcAvgEWD4O0fV29T3qO1OzGVcDyQc5/wvs69UqkZzS9wSI+I5b3egrWiEZfPKWDgXvil0WgVSAd20dnIWK4FjiuQglTYCNQz6E2HlWfznuxYP99U6wp0q0qhPoZ2EFrjEBvD1m8KhB6Ghv2DWGQ5zNgnRrZ7jA7KVBvWG/aOtJhXqmnQH8HlmmQbaZALwDOzACRE7tmsBnpkCWDf9vE9EKFL76bdRPUaG0U37RaB7+vCXyVwfcVgX5ekUNN53tGp0UFGYt0OcRU6PyMBb4T6N8FB6Fujl6Ht2+fSJl8LZrojzYatWzwZhPHMf9uftnEeRPNF6hJYCtgTbQe4FXeJ1oXMCnQRHcL1ID65KaRwZmwUNQnWhEw/myiuQKdU/QJa2b08XlJw7Mq8XeKwntzpcWc4qTvgVWbtm0h/a5G1WwVPQHMikDNscytGfxuSWFpIeGatuwLgHXaKjrKjEkEuhhgQjjmXdJJ/rZfX5AlctwECKaMroxVhuK5WwV4BNiiZIale6OEPtIlwOklgi1w5uP36QVjfsjEkrGpFeePgLsGrTKPaVcMur2UdHwk87xWE/5XUai7SY3c+1J+qNqzRQFjTlsMzq/b2D4+GW0MIYaYjTWhSQdq/tk8tH0VtY7DJAO1cvYkcFto6qi1hEkGGuujuwFPNdn7pAL13fT9zH7fJxGo7p7e0AYhM5kVI08i0NisUVoerDLhSQMa07KPAXs2ncvi75ME1BqQvqt9C1UJ60rskwLUdKxp2YH7m8YF1L4922NMfdi8bPX8Y0ATtMT3eIUqDgzZD8sKQ9V/ugbqDXl9TT9vxFe8WEyrHhJCRlsKTH4J8v42ZzId2yVQC1Y+5GknaJW8atfeJkNCyWZGwenmDdsW26q7s+2GWpz1lmxDmrNZSeNi3buRUZcaTdvrcoS2QUqPZ+TUJVDNUNO1wSmHTNDNaopCchiVjekSqOvZE+Fl5O1ZR/cCx3bZmdY10AguPi/rh3/08XsbnjyTI/l3jyZN/wfBounXN0h+ewAAAABJRU5ErkJggg=="},"407":function(A,e,t){"use strict";t(27);var n=t(1),o=t(71),r=Object.assign||function(A){for(var e=1;e<arguments.length;e++){var t=arguments[e];for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(A[n]=t[n])}return A},i=function(){function defineProperties(A,e){for(var t=0;t<e.length;t++){var n=e[t];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(A,n.key,n)}}return function(A,e,t){return e&&defineProperties(A.prototype,e),t&&defineProperties(A,t),A}}();function _classCallCheck(A,e){if(!(A instanceof e))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(A,e){if(!A)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!e||"object"!=typeof e&&"function"!=typeof e?A:e}var a=function(A){function RichText(){return _classCallCheck(this,RichText),_possibleConstructorReturn(this,(RichText.__proto__||Object.getPrototypeOf(RichText)).apply(this,arguments))}return function _inherits(A,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function, not "+typeof e);A.prototype=Object.create(e&&e.prototype,{"constructor":{"value":A,"enumerable":!1,"writable":!0,"configurable":!0}}),e&&(Object.setPrototypeOf?Object.setPrototypeOf(A,e):A.__proto__=e)}(RichText,A),i(RichText,[{"key":"renderNodes","value":function renderNodes(A){if("text"===A.type)return n.l.createElement("span",{},A.text);var e=this.renderChildrens(A.children),t={"className":"","style":""};if(A.hasOwnProperty("attrs"))for(var o in A.attrs)"class"===o?t.className=A.attrs[o]||"":t[o]=A.attrs[o]||"";return n.l.createElement(A.name,t,e)}},{"key":"renderChildrens","value":function renderChildrens(){var A=this,e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[];if(0!==e.length)return e.map((function(e,t){return"text"===e.type?e.text:A.renderNodes(e)}))}},{"key":"render","value":function render(){var A=this,e=this.props,t=e.nodes,i=e.className,a=function _objectWithoutProperties(A,e){var t={};for(var n in A)e.indexOf(n)>=0||Object.prototype.hasOwnProperty.call(A,n)&&(t[n]=A[n]);return t}(e,["nodes","className"]);return Array.isArray(t)?n.l.createElement("div",r({"className":i},Object(o.a)(this.props,["nodes","className"]),a),t.map((function(e,t){return A.renderNodes(e)}))):n.l.createElement("div",r({"className":i},Object(o.a)(this.props,["className"]),a,{"dangerouslySetInnerHTML":{"__html":t}}))}}]),RichText}(n.l.Component);e.a=a}}]);