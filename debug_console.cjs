const puppeteer = require('puppeteer');

(async () => {
    const browser = await puppeteer.launch({ headless: 'new' });
    const page = await browser.newPage();

    let errors = [];
    page.on('console', msg => {
        if (msg.type() === 'error') {
            const text = msg.text();
            if(!text.includes('favicon')) {
               errors.push(text);
            }
        }
    });
    page.on('pageerror', err => {
        errors.push(err.toString());
    });

    try {
        await page.goto('http://127.0.0.1:8000/admin-masuk', { waitUntil: 'networkidle2' });
        await page.type('#email', 'guru@skripsi.com');
        await page.type('#password', 'password');
        await Promise.all([
            page.click('button[type="submit"]'),
            page.waitForNavigation({ waitUntil: 'networkidle2' })
        ]);

        await page.goto('http://127.0.0.1:8000/modules/6/tasks/create', { waitUntil: 'networkidle2' });
        
        console.log('Errors found:');
        console.log(JSON.stringify(errors, null, 2));

    } catch (e) {
        console.error('Script failed:', e);
    } finally {
        await browser.close();
    }
})();
