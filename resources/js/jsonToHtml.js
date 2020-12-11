export default {
    methods: {
        makeParagraph(obj) {
            return `<p class="text-gray-700 leading-7 mb-4 py-4">${obj.data.text}</p>`
        },

        makeHeader(obj) {
            return `<h${obj.data.level} class="text-gray-800 leading-relaxed py-4">${obj.data.text}</h${obj.data.level}>`
        },

        makeImage(obj) {
            const caption = obj.data.caption ? `<div class="text-gray-700 text-center leading-7 pt-4"><p>${obj.data.caption}</p></div>` : ''
                return `<div class="max-w-lg mx-auto mb-4 p-4">
                    <img src="${obj.data.file.url}" alt="${obj.data.caption}"/>
                    ${caption}
                </div>`
        },

        makeRawHtml(obj) {
            return `<div class="w-full text-gray-800 p-4 mb-4">${obj.data.html}</div>\n`;
        },

        makeCode(obj) {
            return `<section class="w-full bg-gray-800 text-white mb-4 p-4">
                        <code>
                            <pre>${obj.data.code}</pre>
                        </code>
                    </section>`
        },

        makeList(obj) {
            if (obj.data.style === 'unordered') {
                const list = obj.data.items.map(item => `<li class="leading-7 pb-2">- ${item}</li>`);
                return `<ul class="mb-4 p-4">
                            ${list.join('')}
                        </ul>`;
            } else {
                const list = obj.data.items.map((item,index) => `<li class="leading-7 pb-2">${index + 1}. ${item}</li>`);
                return `<ol class="mb-4 p-4">
                            ${list.join('')}
                        </ol>`           
            }
        },

        makeLink(obj) {
            return `<a href="${obj.data.link}" target="blank" class="mb-4 p-4">${obj.data.link}</a>`
        },

        makeQuote(obj) {
            return `<section class="mb-4 p-4 bg-gray-300 text-gray-800">
                        <blockquote class="flex items-center leading-7 text-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="inline-block w-16 h-16 text-gray-400 mx-4" viewBox="0 0 975.036 975.036">
                                <path d="M925.036 57.197h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.399 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l36 76c11.6 24.399 40.3 35.1 65.1 24.399 66.2-28.6 122.101-64.8 167.7-108.8 55.601-53.7 93.7-114.3 114.3-181.9 20.601-67.6 30.9-159.8 30.9-276.8v-239c0-27.599-22.401-50-50-50zM106.036 913.497c65.4-28.5 121-64.699 166.9-108.6 56.1-53.7 94.4-114.1 115-181.2 20.6-67.1 30.899-159.6 30.899-277.5v-239c0-27.6-22.399-50-50-50h-304c-27.6 0-50 22.4-50 50v304c0 27.601 22.4 50 50 50h145.5c-1.9 79.601-20.4 143.3-55.4 191.2-27.6 37.8-69.4 69.1-125.3 93.8-25.7 11.3-36.8 41.7-24.8 67.101l35.9 75.8c11.601 24.399 40.501 35.2 65.301 24.399z"></path>
                            </svg>
                            <span class="inline-block">${obj.data.text}</span>
                        </blockquote>
                        <p class="leading-relaxed pt-4 ml-4">- ${obj.data.caption}</p>
                    </section>`
        },

        makeWarning(obj) {
            return `<section class="mb-4 p-4">
                <div class="flex items-center">
                    <span>
                        <svg class="w-8 h-8 text-orange-400 ml-2 mr-4" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100">
                        <path d="M0 50 a 50,50 0 1,0 100,0 a 50,50 0 1,0 -100,0z M40 15 L60 15 L55 60 L45 60z M42 75 a8,8 0 1,1 16,0 a8,8 0 1,1 -16,0z" fill="currentColor"/>
                        </svg>
                    </span>                     
                    <h3 class="text-gray-700 text-lg">${obj.data.title}</h3>
                </div>
                <p class="text-gray-700 leading-relaxed text-md pt-4">${obj.data.message}</p>
             </section>`
        },

        makeChecklist(obj) {
            const list = obj.data.items.map(item => {
                if (item.checked) {
                    return `<div class="mb-2 leading-relaxed" >
                        <span class="w-5 h-5 inline-block border border-blue-700 bg-blue-700 text-white rounded-full text-center font-bold">&check;</span>
                        ${item.text}</div>`;
                }else{
                    return `<div class="mb-2 leading-relaxed" >
                        <span class="w-5 h-5 inline-block border border-blue-700  text-white rounded-full">&nbsp;</span>
                        ${item.text}</div>`
                }
            });
            return `<section class="p-4">
                        ${list.join('')}
                    </section>`;
        },

        makeDelimeter(obj) {
            return `<hr class="w-1/2 mx-auto">\n`
        },

        makeTable(obj) {
            let table = '<table class="table-auto shadow bg-white mx-auto mb-4 p-4">'
            for (let row of obj.data.content) {
                let rowStr = '<tr>'
                for (let cell of row) {
                    rowStr += `<td class="border px-8 py-4">${cell}</td>`
                }
                rowStr += '</tr>'
                table += rowStr
            }
            return `${table}\n`
        },
    }
}