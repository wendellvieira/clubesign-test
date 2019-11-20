function s4 () {
  return Math.floor((Math.random() * Date.now()) / 0x10000)
    .toString(16)
    .substring(1)
}

export default function () {
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
    s4() + '-' + s4() + s4() + s4()
}
