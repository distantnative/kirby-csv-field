import { defineConfig } from "vitepress";

// https://vitepress.dev/reference/site-config
export default defineConfig({
	title: "CSV field",
	description: "Preview your CSV file in the Panel",
	lang: "en-US",
	base: "/kirby-csv-field/",
	appearance: false,
	cleanUrls: true,
	head: [["link", { rel: "og:image", href: "/kirby-csv-field/ogimage.png" }]],
	themeConfig: {
		nav: [
			{ text: "Home", link: "/" },
			{ text: "Docs", link: "/quickstart" },
			{
				text: "Changelog",
				link: "https://github.com/distantnative/kirby-csv-field/releases",
			},
			{
				text: "Donate",
				link: "https://paypal.me/distantnative",
			},
		],

		search: {
			provider: "local",
		},

		sidebar: [
			{
				text: "Documentation",
				items: [
					{ text: "Getting started", link: "/quickstart" },
					{ text: "Panel field", link: "/field" },
					{ text: "Use in templates", link: "/templates" },
				],
			},
			{
				text: "Support development",
				items: [
					{
						text: "Donate",
						link: "https://paypal.me/distantnative",
					},
				],
			},
		],

		socialLinks: [
			{
				icon: "github",
				link: "https://github.com/distantnative/retour-for-kirby",
			},
			{
				icon: "mastodon",
				link: "https://chaos.social/@distantnative",
			},
		],

		footer: {
			message:
				"CSV field is made for <a href='https://getkirby.com'>Kirby CMS</a> with love 💛",
		},
	},
});
