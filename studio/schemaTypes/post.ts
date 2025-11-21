// import {defineField, defineType} from 'sanity'

// export default defineType({
//   name: 'post',
//   title: 'Post',
//   type: 'document',
//   fields: [
//     defineField({
//       name: 'title',
//       title: 'Title',
//       type: 'string',
//     }),
//     defineField({
//       name: 'slug',
//       title: 'Slug',
//       type: 'slug',
//       options: {
//         source: 'title',
//         maxLength: 96,
//       },
//     }),
//     defineField({
//       name: 'author',
//       title: 'Author',
//       type: 'reference',
//       to: {type: 'author'},
//     }),
//     defineField({
//       name: 'mainImage',
//       title: 'Main image',
//       type: 'image',
//       options: {
//         hotspot: true,
//       },
//     }),
//     defineField({
//       name: 'categories',
//       title: 'Categories',
//       type: 'array',
//       of: [{type: 'reference', to: {type: 'category'}}],
//     }),
//     defineField({
//       name: 'publishedAt',
//       title: 'Published at',
//       type: 'datetime',
//     }),
//     defineField({
//       name: 'body',
//       title: 'Body',
//       type: 'blockContent',
//     }),
//   ],

//   preview: {
//     select: {
//       title: 'title',
//       author: 'author.name',
//       media: 'mainImage',
//     },
//     prepare(selection) {
//       const {author} = selection
//       return {...selection, subtitle: author && `by ${author}`}
//     },
//   },
// })


import {defineField, defineType} from 'sanity'
import previewUrl from './previewURL'

export default defineType({
  name: 'post',
  title: 'Post',
  type: 'document',

  fields: [
    defineField({
      name: 'title',
      title: 'Title',
      type: 'string',
      validation: (Rule) => Rule.required().error('Title is required'),
    }),

    defineField({
      name: 'slug',
      title: 'Slug',
      type: 'slug',
      options: {
        source: 'title',
        maxLength: 96,
        slugify: (input: string) =>
          input
            .toLowerCase()
            .replace(/[^\w\s-]/g, '')
            .trim()
            .replace(/\s+/g, '-')
            .slice(0, 96),
      },
      validation: (Rule) => Rule.required().error('Slug is required'),
    }),

    defineField({
      name: 'categories',
      title: 'Categories',
      type: 'array',
      of: [{type: 'reference', to: {type: 'category'}}],
      validation: (Rule) => Rule.required().min(1).error('Category is required'),
    }),

    defineField({
      name: 'noindex',
      title: 'Noindex',
      type: 'boolean',
      description: 'Check this to prevent the page from being indexed by search engines.',
    }),

    defineField({
      name: 'metaTitle',
      title: 'Meta Title',
      type: 'string',
    }),

    defineField({
      name: 'metaDescription',
      title: 'Meta Description',
      type: 'text',
    }),

    // ===== BODY (portable text + custom blocks) =====
    defineField({
      name: 'body',
      title: 'Body',
      type: 'array',
      of: [
        // Portable Text
        {
          type: 'block',
          styles: [
            {title: 'Normal', value: 'normal'},
            {title: 'H1', value: 'h1'},
            {title: 'H2', value: 'h2'},
            {title: 'H3', value: 'h3'},
            {title: 'H4', value: 'h4'},
            {title: 'H5', value: 'h5'},
            {title: 'Quote', value: 'blockquote'},
          ],
          lists: [
            {title: 'Bullet', value: 'bullet'},
            {title: 'Numbered', value: 'number'},
          ],
          marks: {
            decorators: [
              {title: 'Strong', value: 'strong'},
              {title: 'Emphasis', value: 'em'},
              {title: 'Underline', value: 'underline'},
              {title: 'Strike', value: 'strike-through'},
            ],
            annotations: [
              {
                name: 'link',
                type: 'object',
                title: 'URL',
                fields: [{name: 'href', type: 'url', title: 'URL'}],
              },
            ],
          },
        },

        // Image (with optional click-through URL)
        {
          type: 'image',
          options: {hotspot: true},
          fields: [
            {
              name: 'url',
              type: 'url',
              title: 'URL',
              description: 'Optional URL to redirect when the image is clicked',
            },
            {name: 'alt', type: 'string', title: 'Alt text'},
          ],
        },

        // ---- Inline custom blocks (named so they are reusable) ----
        {
          name: 'bioBlock',
          title: 'Bio Block',
          type: 'object',
          fields: [
            {name: 'title', type: 'string', title: 'Title'},
            {name: 'bio', type: 'array', title: 'Bio', of: [{type: 'block'}]},
            {name: 'avatar', type: 'image', title: 'Avatar', options: {hotspot: true}},
          ],
          preview: {select: {title: 'title', media: 'avatar'}},
        },

        {
          name: 'video',
          title: 'Video',
          type: 'object',
          fields: [
            {
              name: 'provider',
              type: 'string',
              title: 'Provider',
              options: {list: ['youtube', 'vimeo', 'html5']},
            },
            {name: 'url', type: 'url', title: 'Video URL'},
            {name: 'caption', type: 'string', title: 'Caption'},
            {name: 'poster', type: 'image', title: 'Poster', options: {hotspot: true}},
          ],
          preview: {select: {title: 'caption'}},
        },

        {
          name: 'swiper',
          title: 'Swiper / Carousel',
          type: 'object',
          fields: [
            {
              name: 'slides',
              title: 'Slides',
              type: 'array',
              of: [
                {
                  type: 'image',
                  options: {hotspot: true},
                  fields: [{name: 'alt', type: 'string', title: 'Alt text'}],
                },
              ],
              validation: (Rule) => Rule.min(1).error('Add at least one slide'),
            },
            {name: 'autoplay', type: 'boolean', title: 'Autoplay'},
            {name: 'loop', type: 'boolean', title: 'Loop'},
          ],
          preview: {
            select: {count: 'slides.length'},
            prepare: ({count}) => ({title: `Slides: ${count || 0}`}),
          },
        },

        // If you don't use the @sanity/table plugin, this custom table block avoids "Unknown type"
        {
          name: 'tableBlock',
          title: 'Table',
          type: 'object',
          fields: [
            {
              name: 'rows',
              title: 'Rows',
              type: 'array',
              of: [
                {
                  name: 'row',
                  type: 'object',
                  fields: [
                    {
                      name: 'cells',
                      title: 'Cells',
                      type: 'array',
                      of: [{type: 'string'}],
                    },
                  ],
                },
              ],
            },
            {name: 'hasHeader', type: 'boolean', title: 'First row is header'},
          ],
          preview: {
            select: {rows: 'rows.length'},
            prepare: ({rows}) => ({title: `Table (${rows || 0} rows)`}),
          },
        },

        {
          name: 'rawHtml',
          title: 'Raw HTML',
          type: 'object',
          fields: [{name: 'html', type: 'text', title: 'HTML'}],
          preview: {prepare: () => ({title: 'Raw HTML block'})},
        },

        {
          name: 'companyInfo',
          title: 'Company Info',
          type: 'object',
          fields: [
            {name: 'companyName', type: 'string', title: 'Company Name'},
            {name: 'logo', type: 'image', title: 'Logo', options: {hotspot: true}},
            {name: 'tagline', type: 'string', title: 'Tagline'},
            {name: 'description', type: 'array', title: 'Description', of: [{type: 'block'}]},
            {name: 'phone', type: 'string', title: 'Phone'},
            {name: 'email', type: 'email', title: 'Email'},
            {name: 'website', type: 'url', title: 'Website'},
            {name: 'address', type: 'string', title: 'Address'},
          ],
          preview: {select: {title: 'companyName', media: 'logo'}},
        },
      ],
    }),

    defineField({
      name: 'author',
      title: 'Author',
      type: 'reference',
      to: {type: 'author'},
      validation: (Rule) => Rule.required().error('Author is required'),
    }),

    defineField({
      name: 'socialImage',
      title: 'Social Image',
      type: 'image',
      options: {hotspot: true},
      validation: (Rule) => Rule.required().error('Social Image is required'),
    }),

    defineField({
      name: 'featured',
      title: 'Featured Image',
      type: 'image',
      options: {hotspot: true},
      validation: (Rule) => Rule.required().error('Featured Image is required'),
    }),

    defineField({
      name: 'publishedAt',
      title: 'Updated at',
      type: 'datetime',
      validation: (Rule) => Rule.required().error('Updated at is required'),
    }),

    defineField({
      name: 'excerpt',
      title: 'Excerpt',
      type: 'text',
      validation: (Rule) => Rule.required().error('Excerpt is required'),
    }),

    defineField({
      name: 'mainImage',
      title: 'Main Image',
      type: 'image',
      options: {hotspot: true},
    }),
  ],

  preview: {
    select: {
      title: 'title',
      author: 'author.name',
      media: 'featured',
    },
    prepare(selection) {
      const {author} = selection as {author?: string}
      return {...selection, subtitle: author && `by ${author}`}
    },
  },

  
  
})

