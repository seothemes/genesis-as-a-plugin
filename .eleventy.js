// Eleventy is the static site generator Genesis uses to build documentation.
// - See https://www.11ty.io/.
// - Type `npm run docs` to build documentation in ./docs/_site/.
// - Type `npm run docs:dev` to run a local server to preview docs and refresh during content or Sass changes.

const pluginSyntaxHighlight = require("@11ty/eleventy-plugin-syntaxhighlight");
const widont = require("widont");
const compareVersions = require("compare-versions");

module.exports = function(eleventyConfig) {
	eleventyConfig.addPlugin(pluginSyntaxHighlight);

	// Can pass
	eleventyConfig.addFilter("widont", text => {
		return widont(text);
	});

	// Build a 'changelog' collection from content in the `changelog/` directory.
	eleventyConfig.addCollection("changelog", function(collection) {
		return collection.getFilteredByGlob("./docs/changelog/*.md").sort(function(a, b) {
			const aVersion = a.inputPath.replace('./docs/changelog/','').replace('.md','');
			const bVersion = b.inputPath.replace('./docs/changelog/','').replace('.md','');
			return compareVersions(bVersion, aVersion);
		});
	});

	// Copy css, js, and img directories to the compiled site directory as they are, without further processing.
	eleventyConfig.addPassthroughCopy("docs/css");
	eleventyConfig.addPassthroughCopy("docs/img");
	eleventyConfig.addPassthroughCopy("docs/js");
	eleventyConfig.addPassthroughCopy("docs/favicon.ico");

	// Markdown plugins
	let markdownIt = require("markdown-it");
	let markdownItAnchor = require("markdown-it-anchor");
	let options = {
		html: true,
		breaks: true,
		linkify: true // Ensures bare links become clickable links, such as those in the changelog,
	};
	let opts = {
		permalink: true,
		permalinkClass: "direct-link",
		permalinkSymbol: "#"
	};

	eleventyConfig.setLibrary("md", markdownIt(options)
		.use(markdownItAnchor, opts)
	);

	// Adjust docs collection to add additional data properties that help with menu ordering and hierarchy.
	// - data.section: '1' from '1-00-intro.md'.
	// - data.order: '00' from '1-00-intro.md'.
	// - data.number: '100' from '1-00-intro.md'.
	// - data.children: array of doc objects that should sit below parent derived from filenames ('1-01-about.md' is a child of '1-00-intro.md').
	eleventyConfig.addCollection("docs", function(collection) {
		return collection.getFilteredByTag("docs").map(doc => {
			let levels = doc.inputPath.replace(/[^0-9\-]/g, '')
									  .split('-')
									  .filter(String); // ['1', '00'] from '1-00-intro.md'.
			doc.data.section = levels[0];
			doc.data.order = levels[1];
			doc.data.number = levels[0] + levels[1];
			doc.data.children = false;
			return doc;
		}).sort(function(a, b) { // Sort based on filename prefix (1-00-intro.md comes before 1-01-about.md)
			return a.data.number - b.data.number;
		}).map((doc, index, docs) => { // Add children to parents.
			if (doc.data.order == '00') {
				doc.data.children = docs.filter( d => d.data.section == doc.data.section && d.data.order != '00' );
			}
			return doc;
		});
	});

	return {
		templateFormats: ["md", "njk", "html", "liquid"],
		pathPrefix: "/",
		markdownTemplateEngine: "liquid",
		htmlTemplateEngine: "njk",
		dataTemplateEngine: "njk",
		passthroughFileCopy: true,
		dir: {
			input: "./docs",
			output: "./docs/_site"
		}
	};
};
