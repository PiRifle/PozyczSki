const model = require("../data/models.json");
const ChromeLauncher = require("chrome-launcher");
const prompt = require("prompt-sync")();

console.log("starting");
model.forEach((a) => {
  a.skis.forEach((b) => {
    console.log(`https://www.google.com/search?q=${(
        a.brand +
        " " +
        b.model
      ).replaceAll(" ", "+")}`)
    const cena = prompt("cena:");
    const img = prompt("link do zdjęcia:");
    b.cost = cena;
    b.img = img;
    console.log(b);
  });
});

console.log(JSON.stringify(model));
