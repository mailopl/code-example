﻿//------------------------------------------------------------------------------
// <auto-generated>
//     This code was generated by a tool.
//     Runtime Version:2.0.50727.3053
//
//     Changes to this file may cause incorrect behavior and will be lost if
//     the code is regenerated.
// </auto-generated>
//------------------------------------------------------------------------------

namespace Toodie.Properties {
    
    
    [global::System.Runtime.CompilerServices.CompilerGeneratedAttribute()]
    [global::System.CodeDom.Compiler.GeneratedCodeAttribute("Microsoft.VisualStudio.Editors.SettingsDesigner.SettingsSingleFileGenerator", "9.0.0.0")]
    internal sealed partial class Settings : global::System.Configuration.ApplicationSettingsBase {
        
        private static Settings defaultInstance = ((Settings)(global::System.Configuration.ApplicationSettingsBase.Synchronized(new Settings())));
        
        public static Settings Default {
            get {
                return defaultInstance;
            }
        }
        
        [global::System.Configuration.ApplicationScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("1.0.0.0")]
        public string Version {
            get {
                return ((string)(this["Version"]));
            }
        }
        
        [global::System.Configuration.ApplicationScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("http://odd-it.pl/toodie/versionInfo.xml")]
        public string xmlURL {
            get {
                return ((string)(this["xmlURL"]));
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("pl-PL")]
        public string Language {
            get {
                return ((string)(this["Language"]));
            }
            set {
                this["Language"] = value;
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("False")]
        public bool hideNotes {
            get {
                return ((bool)(this["hideNotes"]));
            }
            set {
                this["hideNotes"] = value;
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("False")]
        public bool openLatestDB {
            get {
                return ((bool)(this["openLatestDB"]));
            }
            set {
                this["openLatestDB"] = value;
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("False")]
        public bool hideProjects {
            get {
                return ((bool)(this["hideProjects"]));
            }
            set {
                this["hideProjects"] = value;
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("False")]
        public bool hideFinished {
            get {
                return ((bool)(this["hideFinished"]));
            }
            set {
                this["hideFinished"] = value;
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("False")]
        public bool onClickEdit {
            get {
                return ((bool)(this["onClickEdit"]));
            }
            set {
                this["onClickEdit"] = value;
            }
        }
        
        [global::System.Configuration.ApplicationScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.SpecialSettingAttribute(global::System.Configuration.SpecialSetting.ConnectionString)]
        [global::System.Configuration.DefaultSettingValueAttribute("Data Source=|DataDirectory|\\Resources\\empty_database.sdf")]
        public string empty_databaseConnectionString {
            get {
                return ((string)(this["empty_databaseConnectionString"]));
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("")]
        public string lastDatabaseFile {
            get {
                return ((string)(this["lastDatabaseFile"]));
            }
            set {
                this["lastDatabaseFile"] = value;
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute("")]
        public string lastTenDatabases {
            get {
                return ((string)(this["lastTenDatabases"]));
            }
            set {
                this["lastTenDatabases"] = value;
            }
        }
        
        [global::System.Configuration.UserScopedSettingAttribute()]
        [global::System.Diagnostics.DebuggerNonUserCodeAttribute()]
        [global::System.Configuration.DefaultSettingValueAttribute(@"<?xml version=""1.0""?>
<ss:Workbook xmlns:ss=""urn:schemas-microsoft-com:office:spreadsheet"">
    <ss:Styles>
        <ss:Style ss:ID=""1"">
            <ss:Font ss:Bold=""1""/>
        </ss:Style>
    </ss:Styles>
    <ss:Worksheet ss:Name=""Sheet1"">
        <ss:Table>
            <ss:Column ss:Width=""100""/>
            <ss:Column ss:Width=""250""/>
            <ss:Column ss:Width=""100""/>
            <ss:Row ss:StyleID=""1"">
                <ss:Cell>
                    <ss:Data ss:Type=""String"">Date</ss:Data>
                </ss:Cell>
                <ss:Cell>
                    <ss:Data ss:Type=""String"">Content</ss:Data>
                </ss:Cell>
                <ss:Cell>
                    <ss:Data ss:Type=""String"">Project</ss:Data>
                </ss:Cell>
            </ss:Row>")]
        public string xmlHeader {
            get {
                return ((string)(this["xmlHeader"]));
            }
            set {
                this["xmlHeader"] = value;
            }
        }
    }
}
