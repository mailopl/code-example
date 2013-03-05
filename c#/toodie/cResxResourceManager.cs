using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Resources;
using System.Globalization;
using System.Reflection;
namespace Toodie
{
    public class ResxResourceManager : ResourceManager
    {
        public ResxResourceManager(string baseName, string resourceDir)
        {
            BaseNameField = baseName;
            ResourceSets = new System.Collections.Hashtable();

            Type baseType = GetType().BaseType;
            BindingFlags flags = BindingFlags.Instance | BindingFlags.NonPublic | BindingFlags.SetField;

            baseType.InvokeMember("moduleDir", flags, null, this, new object[] { resourceDir });
            baseType.InvokeMember("_userResourceSet", flags, null, this, new object[] { typeof(ResXResourceSet) });
            baseType.InvokeMember("UseManifest", flags, null, this, new object[] { false });
        }

        protected override string GetResourceFileName(CultureInfo culture)
        {
            string resourceFileName = base.GetResourceFileName(culture);
            
            return resourceFileName.Replace(".resources", ".resx");
        }
    }
}
