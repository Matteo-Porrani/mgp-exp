


function writeNewCookie(key, val) {
  const encodedVal = encodeURIComponent(val);
  document.cookie = `${key}=${encodedVal}; path=/`;
}


function getCookieArray() {
  const cookies = decodeURIComponent(document.cookie);
  const cookiesArray = cookies.split('; ');
  const cookiesArrayDeep = cookiesArray.map(cookie => {
    return cookie.split('=');
  });

  return cookiesArrayDeep;
}


function getCookieValue(key) {
  const cookies = getCookieArray();
  return cookies.find(item => item[0] === key)[1];
}