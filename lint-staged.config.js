const path = require("path");

module.exports = {
  "**/*.{html,css,js,json,md}": ["prettier --write"],
  "**/*.php": (filenames) => {
    const relativePaths = filenames.map((file) =>
      path.relative(process.cwd(), file),
    );

    const uid = process.getuid ? process.getuid() : 1000;
    const gid = process.getgid ? process.getgid() : 1000;
    const cwd = process.cwd();

    return `docker run --rm --user ${uid}:${gid} -v ${cwd}:/data cytopia/php-cs-fixer fix ${relativePaths.join(" ")}`;
  },
};
