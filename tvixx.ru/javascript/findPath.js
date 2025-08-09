function findPath(fa, fb, arr, s, level) {
    var al = 0;
    if (s == 0) {
        al = 2;
    } else if (s == 1) {
        al = 2;
    } else if (s == 2) {
        al = 1;
    } else if (s == 3) {
        al = 1;
    }

    var arr1 = [];
    var arr2 = [];
    var arr3 = [];
    for (var i = 0; i < arr.length; i++) {
        arr1[arr[i].id] = [];
        for (var x = 1; x < 11; x++) {
            arr1[arr[i].id] = arr[i];
        }
    }
    for (var i = 0; i < arr.length; i++) {
        arr2[arr[i].id] = [];
        for (var x = 1; x < 11; x++) {
            if (arr[i]["IdLoc" + x] != "0" && arr[i]["IdLoc" + x] != "23") {
                if ((arr1[arr[i]["IdLoc" + x]]["access"] == al || arr1[arr[i]["IdLoc" + x]]["access"] == 3) && arr1[arr[i]["IdLoc" + x]]["accesslevel"] <= level) {
                    arr2[arr[i].id].push(arr[i]["IdLoc" + x]);
                }
            }
        }
    }
    function recurFindPath(a, b, p) {
        var t = p.slice();
        t.push("");
        for (var i = 0; i < arr2[a].length; i++) {
            if (t.indexOf(arr2[a][i]) == -1) {
                if (arr2[a][i] == b) {
                    t[t.length - 1] = arr2[a][i];
                    arr3.push(t);
                    break;
                } else {
                    t[t.length - 1] = arr2[a][i];
                    recurFindPath(arr2[a][i], b, t);
                }
            }
        }
    }
    recurFindPath(fa + "", fb + "", [fa + ""]);
    arr3.ourSortAsc(arr3);
    return arr3;
}
Array.prototype.ourSortAsc = function (array) {
  return array.sort(function(a, b) {
    return a.length - b.length;
  });
};
