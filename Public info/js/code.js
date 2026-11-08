const urlBase = "http://COP4331-5.com/LAMPAPI";
const extension = "php";

let userId = 0;
let firstName = "";
let lastName = "";

function doLogin() {
  userId = 0;
  firstName = "";
  lastName = "";

  const login = document.getElementById("login-name").value;
  const password = document.getElementById("login-password").value;

  document.getElementById("login-result").innerHTML = "";

  const tmp = { login, password };
  const jsonPayload = JSON.stringify(tmp);
  const url = `${urlBase}/Login.${extension}`;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        const jsonObject = JSON.parse(xhr.responseText);
        userId = jsonObject.id;

        if (userId < 1) {
          document.getElementById("login-result").innerHTML =
            "User/Password combination incorrect";
          return;
        }

        firstName = jsonObject.firstName;
        lastName = jsonObject.lastName;

        saveCookie();
        window.location.href = "color.html";
      }
    };
    xhr.send(jsonPayload);
  } catch (err) {
    document.getElementById("login-result").innerHTML = err.message;
  }
}

function saveCookie() {
  const minutes = 20;
  const date = new Date();
  date.setTime(date.getTime() + minutes * 60 * 1000);

  // biome-ignore lint/suspicious/noDocumentCookie: Not using api
  document.cookie = `firstName=${firstName},lastName=${lastName},userId=${userId};expires=${date.toGMTString()}`;
}

function readCookie() {
  userId = -1;
  const data = document.cookie;
  const splits = data.split(",");

  for (const thisOne of splits) {
    const tokens = thisOne.trim().split("=");
    if (tokens[0] === "firstName") {
      firstName = tokens[1];
    } else if (tokens[0] === "lastName") {
      lastName = tokens[1];
    } else if (tokens[0] === "userId") {
      userId = parseInt(tokens[1].trim(), 10);
    }
  }

  if (userId < 0) {
    window.location.href = "index.html";
  }
}

function doLogout() {
  userId = 0;
  firstName = "";
  lastName = "";

  // biome-ignore lint/suspicious/noDocumentCookie: Not using api
  document.cookie = "firstName= ; expires = Thu, 01 Jan 1970 00:00:00 GMT";
  window.location.href = "index.html";
}

function addColor() {
  const newColor = document.getElementById("color-text").value;
  document.getElementById("color-add-result").innerHTML = "";

  const tmp = { color: newColor, userId };
  const jsonPayload = JSON.stringify(tmp);
  const url = `${urlBase}/AddColor.${extension}`;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        document.getElementById("color-add-result").innerHTML =
          "Color has been added";
      }
    };
    xhr.send(jsonPayload);
  } catch (err) {
    document.getElementById("color-add-result").innerHTML = err.message;
  }
}

function searchColor() {
  const srch = document.getElementById("searchText").value;
  document.getElementById("colorSearchResult").innerHTML = "";

  let colorList = "";

  const tmp = { search: srch, userId };
  const jsonPayload = JSON.stringify(tmp);
  const url = `${urlBase}/SearchColors.${extension}`;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = function () {
      if (this.readyState === 4 && this.status === 200) {
        document.getElementById("color-search-result").innerHTML =
          "Color(s) has been retrieved";
        const jsonObject = JSON.parse(xhr.responseText);

        for (let i = 0; i < jsonObject.results.length; i++) {
          colorList += jsonObject.results[i];
          if (i < jsonObject.results.length - 1) {
            colorList += "<br />\r\n";
          }
        }

        document.getElementById("color-search-result").innerHTML = colorList;
      }
    };
    xhr.send(jsonPayload);
  } catch (err) {
    document.getElementById("color-search-result").innerHTML = err.message;
  }
}

document.getElementById("login-button")?.addEventListener("click", doLogin);
document.getElementById("logout-button")?.addEventListener("click", doLogout);
document
  .getElementById("search-color-button")
  ?.addEventListener("click", searchColor);
document
  .getElementById("add-color-button")
  ?.addEventListener("click", addColor);

if (document.getElementById("logged-in-div")) {
  readCookie();
}
