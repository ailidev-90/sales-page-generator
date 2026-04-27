# Submission Notes

## Approach

I built a compact Laravel 11 application focused on the requested assessment flow: authenticated users submit product details, receive structured sales page content, save it, preview it, edit/regenerate it, delete it, and export it as standalone HTML.

## Tools Used

- Laravel 11 application structure
- Breeze-style authentication controllers and Blade views
- Blade components and layouts
- Tailwind CSS through Vite
- Laravel HTTP client for OpenAI calls
- SQLite for simple local development

## Main Logic

The primary feature is handled by `SalesPageController` and `AiSalesPageService`.

`SalesPageController` validates input, scopes records to the authenticated user, stores generated JSON, and renders CRUD, preview, and export screens.

`AiSalesPageService` builds a conversion-focused prompt, calls the OpenAI API when configured, safely parses JSON, normalizes the expected fields, and falls back to deterministic generated content if anything goes wrong.

## Database Usage

The app uses a `sales_pages` table with a foreign key to `users`. Raw input fields are stored alongside the generated structured JSON in `generated_content`, which keeps previews and exports consistent without re-calling the AI provider.

## AI Integration

OpenAI is configured with:

```env
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4o-mini
```

The app works without an API key. Missing keys, failed requests, and invalid JSON all trigger fallback content generation.

## Known Limitations

- The exported HTML uses Tailwind CDN for portability instead of compiled project CSS.
- The AI request is synchronous, so very slow provider responses will keep the form waiting.
- The social proof section is intentionally a placeholder because no real testimonials are collected.
- Password reset and email verification are not included because the assessment only required register, login, and logout.

## Future Improvements

- Queue AI generation for longer-running requests.
- Add richer template customization and brand settings.
- Add version history for regenerated pages.
- Add shareable public preview links.
- Add automated browser tests for the full authenticated flow.
