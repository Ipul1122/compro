const fs = require('fs');
const path = require('path');

function replaceInDir(dir) {
    const files = fs.readdirSync(dir);
    for (const file of files) {
        const fullPath = path.join(dir, file);
        if (fs.statSync(fullPath).isDirectory()) {
            replaceInDir(fullPath);
        } else if (fullPath.endsWith('.vue')) {
            let content = fs.readFileSync(fullPath, 'utf8');
            content = content.replace(/'\/admin\//g, "'/direktur/");
            content = content.replace(/"\/admin\//g, '"/direktur/');
            content = content.replace(/`\/admin\//g, '`/direktur/');
            content = content.replace(/@\/components\/admin\//g, '@/components/direktur/');
            
            // Di DashboardDirekturView, Navbar masih ada breadcrumbs yang nge-link ke /admin/...
            content = content.replace(/'\/admin'/g, "'/direktur'");
            
            fs.writeFileSync(fullPath, content);
        }
    }
}

replaceInDir('c:\\My Web Sites\\compro\\frontend\\src\\views\\direktur');
replaceInDir('c:\\My Web Sites\\compro\\frontend\\src\\components\\direktur');
console.log('Selesai');
